<?php

/* -------------------------------------------------------
 *
 *   LiveStreet (v1.0)
 *   Plugin Role (v.0.6)
 *   Copyright © 2011 Bishovec Nikolay
 *
 * --------------------------------------------------------
 *
 *   Plugin Page: http://netlanc.net
 *   Contact e-mail: netlanc@yandex.ru
 *
  ---------------------------------------------------------
 */

class PluginRole_ModulePeople_MapperPeople extends Mapper
{

    public function DeleteUserById($sUserId)
    {
        $sql = "DELETE FROM " . Config::Get('db.table.user') . " WHERE `user_id` = ?";
        return $this->oDb->query($sql, $sUserId);
    }

    public function GetUserList(&$iCount, $iCurrPage, $iPerPage, $aFilter = Array(), $aSort = Array())
    {
        $aReturn = array();

        $sFieldList =
            "u.*,
          IF(ua.user_id IS NULL,0,1) as user_is_administrator,

          session_ip_create, session_ip_last, session_date_create, session_date_last
          ";
        $sWhere = $this->BuildUserFilter($aFilter);
        $sOrder = $this->BuildUserSort($aSort);

        $sql =
            "SELECT " . $sFieldList . "
          FROM
              " . Config::Get('db.table.user') . " AS u
          LEFT JOIN " . Config::Get('db.table.user_administrator') . " AS ua ON u.user_id=ua.user_id
          LEFT JOIN " . Config::Get('db.table.session') . " AS us ON u.user_id=us.user_id
          WHERE
              " . $sWhere . "
          ORDER BY " . $sOrder . "
          LIMIT ?d, ?d
          ";

        $aRows = $this->oDb->selectPage($iCount, $sql, ($iCurrPage - 1) * $iPerPage, $iPerPage);

        if ($aRows) {
            foreach ($aRows as $aRow) {
                $aReturn[] = Engine::GetEntity('User', $aRow); //new User_ModuleAdmin_EntityUser($aRow);
            }
        }
        return $aReturn;
    }

    protected function BuildUserFilter($aFilter)
    {
        $sWhere = '(1=1) ';
        if ($aFilter) {
            if (isset($aFilter['login']))
                $sWhere .= "AND (user_login='" . $aFilter['login'] . "') ";
            if (isset($aFilter['like']))
                $sWhere .= "AND (user_login LIKE '" . $aFilter['like'] . "%') ";
            if (isset($aFilter['admin']))
                $sWhere .= "AND (ua.user_id>0) ";
            if (isset($aFilter['ip'])) {
                $ip1 = $ip2 = $aFilter['ip'];
                if (strpos($aFilter['ip'], '*') !== false) {
                    $ip1 = str_replace('*', '0', $ip1);
                    $ip2 = str_replace('*', '255', $ip2);
                }
                /* form 0.3
              $sWhere.="AND (".
              "(INET_ATON(user_ip_register) BETWEEN INET_ATON('".$ip1."') AND INET_ATON('".$ip2."')) OR ".
              "(INET_ATON(user_ip_last) BETWEEN INET_ATON('".$ip1."') AND INET_ATON('".$ip2."')) ".
              ")";
             *
             */
                $sWhere .= "AND (" .
                    "(INET_ATON(user_ip_register) BETWEEN INET_ATON('" . $ip1 . "') AND INET_ATON('" . $ip2 . "')) " .
                    ")";
            }
            if (isset($aFilter['regdate'])) {
                $nY = intVal(substr($aFilter['regdate'], 0, 4));
                if ($nY)
                    $sWhere .= "AND (YEAR(user_date_register)=" . $nY . ") ";
                if (strlen($aFilter['regdate']) > 5) {
                    $nM = intVal(substr($aFilter['regdate'], 5, 2));
                    if ($nM)
                        $sWhere .= "AND (MONTH(user_date_register)=" . $nM . ") ";
                }
                if (strlen($aFilter['regdate']) > 8) {
                    $nD = intVal(substr($aFilter['regdate'], 8, 2));
                    if ($nD)
                        $sWhere .= "AND (DAYOFMONTH(user_date_register)=" . $nD . ") ";
                }
            }
        }
        return $sWhere;
    }

    protected function BuildUserSort($aSort)
    {
        $sSort = '';
        if (isset($aSort['id'])) {
            $sSort = 'user_id ';
            if ($aSort['id'] == 1)
                $sSort .= 'ASC'; else
                $sSort .= 'DESC';
        }
        if (isset($aSort['login'])) {
            $sSort = 'user_login ';
            if ($aSort['login'] == 1)
                $sSort .= 'ASC'; else
                $sSort .= 'DESC';
        }
        if (isset($aSort['regdate'])) {
            $sSort = 'user_date_register ';
            if ($aSort['regdate'] == 1)
                $sSort .= 'ASC'; else
                $sSort .= 'DESC';
        }
        if (isset($aSort['reg_ip'])) {
            $sSort = 'INET_ATON(user_ip_register) ';
            if ($aSort['reg_ip'] == 1)
                $sSort .= 'ASC'; else
                $sSort .= 'DESC';
        }
        if (isset($aSort['activated'])) {
            $sSort = 'user_date_activate ';
            if ($aSort['activated'] == 1)
                $sSort .= 'ASC'; else
                $sSort .= 'DESC';
        }

        if (isset($aSort['last_date'])) {
            $sSort = 'session_date_last ';
            if ($aSort['last_date'] == 1)
                $sSort .= 'ASC'; else
                $sSort .= 'DESC';
        }
        if (isset($aSort['last_ip'])) {
            $sSort = 'INET_ATON(session_ip_last) ';
            if ($aSort['last_ip'] == 1)
                $sSort .= 'ASC'; else
                $sSort .= 'DESC';
        }

        if (!$sSort)
            $sSort = 'user_id ASC';
        return ($sSort);
    }

}

?>
