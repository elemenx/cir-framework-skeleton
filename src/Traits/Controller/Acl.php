<?php

namespace Elemenx\CirFrameworkSkeleton\Traits\Controller;

use Illuminate\Support\Str;

trait Acl
{
    public function handleAcl($acls = [], $action = 'merge')
    {
        return call_user_func_array([$this, $action . 'Acl'], [$acls]);
    }

    private function mergeAcl($acls, $acl_name = '')
    {
        $acls_temp = $acls;

        foreach ($acls as $key => &$acl) {
            $acl = trim($acl);

            if (substr($acl, 0, 1) != '!' && substr($acl, -1, 1) == '*') {
                $acls_temp = array_intersect($this->processAcl($acls, substr($acl, 0, strlen($acl) - 2)), $acls_temp);
            }
        }

        return $acls_temp;
    }

    private function exceptAcl($acls, $acl_name = '')
    {
        $acls_temp = $acls;

        foreach ($acls as $key => &$acl) {
            $acl = trim($acl);

            if (substr($acl, 0, 1) == '!' && substr($acl, -1, 1) == '*') {
                $acls_temp = array_intersect($this->processAcl($acls, substr($acl, 0, strlen($acl) - 2)), $acls_temp);
            }
        }

        return $acls_temp;
    }

    private function processAcl($acls, $acl_name)
    {
        foreach ($acls as $key => &$acl) {
            if ($this->isMergeable($acl_name, $acl)) {
                unset($acls[$key]);
            }
        }

        return $acls;
    }

    private function isMergeable($acl_name, $acl)
    {
        if (!empty($acl_name) && Str::startsWith($acl, $acl_name)) {
            $explode_acl_name = explode('.', $acl_name);
            $explode_acl = explode('.', $acl);

            if ($acl != $acl_name . '.*' && $explode_acl_name[0] == $explode_acl[0]) {
                return true;
            }
        }

        return false;
    }
}
