<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-chalkboard-teacher"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Pendaftaran</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- QUERY MENU BERDASARKAN ROLEID/LEVEL -->
    <?php
    $role_id = $this->session->userdata('roleid');
    $queryMenu = " SELECT a.id, a.menu 
                    FROM menu a JOIN user_access_menu b
                    ON b.menu_id = a.id
                    WHERE b.role_id = '" . $role_id . "'
                    ORDER BY b.menu_id Asc
                ";
    $menu = $this->db->query($queryMenu)->result_array();
    ?>

    <!-- LOOPING MENU -->
    <?php foreach ($menu as $m) : ?>

        <!-- Heading -->
        <div class="sidebar-heading">
            <?= $m['menu']; ?>
        </div>

        <!-- SIAPKAN SUBMENU SESUAI MENU -->
        <?php
        $id = $m['id'];
        $querySubMenu = "SELECT a.* 
                        FROM sub_menu a
                        WHERE a.menu_id = '" . $id . "' ";
        $subMenu   = $this->db->query($querySubMenu)->result_array();
        ?>

        <!-- LOOPING MENU -->
        <?php foreach ($subMenu as $sm) : ?>

            <!-- Nav Item - Dashboard -->
            <?php if ($judul == $sm['title']) : ?>
                <li class="nav-item active">
                <?php else : ?>
                <li class="nav-item">
                <?php endif; ?>
                <a class="nav-link pb-0" href="<?php echo base_url($sm['url']) ?>">
                    <i class="<?= $sm['icon'];  ?>"></i>
                    <span><?= $sm['title']; ?></span></a>
                </li>



            <?php endforeach; ?>

            <!-- Divider -->
            <hr class="sidebar-divider mt-3">

        <?php endforeach; ?>

        <!-- Nav Item - Logout -->
        <li class="nav-item">
            <a class="nav-link" href="<?php echo base_url('auth/logout') ?>">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span></a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

</ul>
<!-- End of Sidebar -->