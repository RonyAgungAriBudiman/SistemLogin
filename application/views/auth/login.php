<div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center mt-5">

        <div class="col-lg-7">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Halaman Login</h1>
                                </div>

                                <?php echo $this->session->flashdata('message'); ?>

                                <form class="user" method="post" action="<?php echo base_url() ?>auth">
                                    <div class="form-group">
                                        <input type="text" name="email" class="form-control form-control-user" id="email" placeholder="Masukan email..." value="<?php echo set_value('email') ?>">
                                        <?php echo form_error('email', '<small class="text-danger pl-3">', '</small>') ?>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="password" class="form-control form-control-user" id="password" placeholder="Password" value="<?php echo set_value('password') ?>">
                                        <?php echo form_error('password', '<small class="text-danger pl-3">', '</small>') ?>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Login
                                    </button>
                                </form>
                                <div class="text-center">
                                    <a class="small" href="<?php echo base_url() ?>auth/forgotpassword">Lupa Password?</a>
                                </div>
                                <div class="text-center">
                                    <a class="small" href="<?php echo base_url() ?>auth/registrasi">Buat Akun!</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>