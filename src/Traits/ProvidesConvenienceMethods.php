<?php

namespace  Elemenx\CirFrameworkSkeleton\Traits;

use stdClass;
use Illuminate\Http\Resources\Json\JsonResource;
use ElemenX\ApiPagination\Paginator as ElemenXPaginator;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Pagination\Paginator as IlluminatePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

trait ProvidesConvenienceMethods
{
    public function success($data = [], $code = 200, $params = [])
    {
        $result = [
            'code'    => $code,
            'message' => $code == 200 ? 'OK' : $this->succussMsg($code, $params),
            'data'    => []
        ];

        if (is_object($data)) {
            if ($data instanceof JsonResource) {
                $result['data'] = $data->resolve();
                if (method_exists($data, 'getMeta')) {
                    $result['meta'] = $data->getMeta();
                }
            } elseif ($data instanceof ElemenXPaginator) {
                $data = $data->toArray();
                $result['data'] = $data['data'];
                $result['meta'] = $data['meta'];
            } elseif ($data instanceof CursorPaginator) {
                $data_arr = $data->toArray();
                $result['data'] = $data_arr['data'];
                $result['meta'] = [
                    'path'        => $data->path(),
                    'per_page'    => $data->perPage(),
                    'next_cursor' => Arr::get(parse_url($data->nextPageUrl()), 'query'),
                    'prev_cursor' => Arr::get(parse_url($data->previousPageUrl()), 'query')
                ];
            } elseif ($data instanceof IlluminatePaginator) {
                $data = $data->toArray();
                $result['data'] = $data['data'];
                $result['meta'] = Arr::only($data, ['current_page', 'per_page', 'to']);
            } else {
                if (method_exists($data, 'toArray')) {
                    $data = $data->toArray();
                }

                $result['data'] = $data;
            }
        } elseif (is_null($data)) {
            $result['data'] = new stdClass;
        } else {
            $result['data'] = $data;
        }

        $meta = Arr::get($result, 'meta');

        if (is_null($meta)) {
            unset($result['meta']);
        }

        return response()->json($result, substr($code, 0, 3));
    }

    public function error($code = 400, $params = [], $msg = '')
    {
        return response()->json([
            'code'    => $code,
            'message' => $this->errorMsg($code, $params, $msg)
        ], substr($code, 0, 3));
    }

    protected function user()
    {
        return Auth::user();
    }

    protected function successMsg($code = 200, $params = [], $msg = '')
    {
        return $this->getReturnMsg('success', $code, $params, $msg);
    }

    protected function errorMsg($code, $params, $msg)
    {
        return $this->getReturnMsg('errors', $code, $params, $msg);
    }

    private function getReturnMsg($type, $code, $params, $msg)
    {
        if (!empty($msg)) {
            return $msg;
        }

        $msg = trans($type . '.' . $code, $params);
        if (trans($type . '.' . $code, $params) == $type . '.' . $code) {
            $msg = str_replace('cir_framework_skeleton::', '', trans('cir_framework_skeleton::' . $type . '.' . $code, $params));
        }
        return $msg;
    }
}
