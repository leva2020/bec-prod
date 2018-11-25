<?php include'top.php'; ?>

<?php include'topbar.php'; ?>

<div id="site">
	<?php include'header.php'; ?>
	<?php include'breadcrumb.php'; ?>
	
	<section class="content">
		<div class="container">

			<!--header page-->
			<div class="row">
				<div class="header-section col-md-12 col-xs-12">
					<h1 class="color-text-blue">Nosotros</h1>
				</div>
			</div>

			<div class="row">

				<!--menu lateral-->
				<div id="left_col" class="col-sm-4 col-lg-3 pull-left">
					<?php include'content-nav-panel.php'; ?>
					
					<div class="promo_pods ">
						<?php include'content-promopods-default-panel.php'; ?>
						<?php include'content-promopods-default-panel-color.php'; ?>
						<?php include'content-promopods-image-panel.php'; ?>
					</div>
				</div>

				<!--contenido-->
				<div id="right_col" class="col-sm-8 pull-right" role="main">
					<?php include'page-component-image.php'; ?>
					<?php include'page-component-textblock.php'; ?>
					<?php include'page-component-accordion.php'; ?>
		            <?php include'page-component-textblock.php'; ?>

		            <hr>

		            <?php include'page-component-listing.php'; ?>
		            <?php include'page-component-textblock-title.php'; ?>
		            <?php include'page-component-gallery.php'; ?>
				</div>

			</div>
		</div>
	</section>
	
	<?php include'section-participantes.php'; ?>
	<?php include'footer.php'; ?>
</div>

<?php include'bottom.php'; ?>