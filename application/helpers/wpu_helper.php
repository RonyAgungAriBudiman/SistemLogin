<?php
function is_logged_in()
{

    $ci = get_instance();
    //jika session email tidak ada
    if (!$ci->session->userdata('email')) {
        redirect('auth');
    } else {

        $role_id = $ci->session->userdata('roleid');

        //base_url()/segmen_1/segmen_2
        $menu = $ci->uri->segment(1);

        //menampilkan table menu berdasarkan menu dari segmen 1
        $queryMenu = $ci->db->get_where('menu', ['menu' => $menu])->row_array();

        //id dari table menu
        $menu_id = $queryMenu['id'];

        //select table user_access_menu where role_id dan menu_id
        $userAccess = $ci->db->get_where('user_access_menu', [
            'role_id' => $role_id,
            'menu_id' => $menu_id
        ]);

        //jika data tidak ada
        if ($userAccess->num_rows() < 1) {
            redirect('Auth/blocked');
        }
    }
}

function check_access($role_id, $menu_id)
{
    $ci = get_instance();

    $result =   $ci->db->get_where('user_access_menu', ['role_id' => $role_id, 'menu_id' => $menu_id]);

    if ($result->num_rows() > 0) {
        return "checked='checked'";
    }
}
