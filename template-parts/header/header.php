<!-- Start Header Top -->
<section class="top-bar-area-wrapper d-none d-lg-block">
	<div class="container">
		<div class="row">
			<div class="col-lg-4">
				<div class="top-bar-left d-flex">
					<?php booTopMenuLeft(); ?>
				</div>
			</div>
			<div class="col-lg-8">
				<div class="top-bar-right">
					<nav class="top-bar-menu">
						<?php booTopMenuRight(); ?>
					</nav>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- End Header Top -->

<!-- Start Main Header  -->
<header class="header-area-wrapper">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-2 col-md-8 col-sm-7 col">
				<div class="boo-site-logo ">
					<?php echo boo_header_logo(); ?>
				</div>
			</div>
			<div class="col-lg-10 col-md-4 col-sm-5 col">
				<div class="header-area-right d-flex align-items-center justify-content-end">
					<nav class="main-menu-wrapper boo-main-menu ">
						<?php boo_header_menu(); ?>
					</nav>
					<div class="header-right-login-btn">
						<a target="_black"
							href="<?php echo esc_url( get_theme_mod( 'boo_main_menu_top_login_url' ) ); ?>">
							<img src="<?php echo BOO_THEME_IMG_DIR . 'user.svg'; ?>">
							<span>
								<?php echo esc_html__( get_theme_mod( 'boo_main_menu_top_login' ), 'boo-energy' ); ?>
							</span>
						</a>
					</div>
				</div>
				<div class="boo-mobile-header-menu d-flex align-items-center justify-content-end ">
					<div class="boo-hamburger-menu-search">
						<span class="top-bar-search-icon"><img
								src="<?php echo BOO_THEME_IMG_DIR . 'search.svg'; ?>"><?php echo esc_html__( 'Sök', 'boo-energy' ); ?></span>
					</div>
					<div class="boo-hamburger-menu">
						<span></span>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php
	require_once 'header-mobile.php';

	?>
</header>
<!-- End Main Header -->


<div id="boo-content">
	<!-- Page content will be loaded here -->
</div>