<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu_model extends CI_Model
{
    public function getSubMenu()
    {
        $query = "SELECT a.*, b.menu 
                    FROM sub_menu a JOIN menu b 
                    ON b.id = a.menu_id ";
        return $this->db->query($query)->result_array();
    }
}
