<?php include'top.php'; ?>

<?php include'topbar.php'; ?>

<div id="site">
	<?php include'header.php'; ?>
	<?php include'breadcrumb.php'; ?>
	
	<section class="content">
		<div class="container">
			<div class="row">
				<!--menu horizontal-->
				<div class="promo_pods">
					<div class="col-md-4 col-sm-6 col-xs-12">
						<div class="item panel panel-default color-bg-main-blue">
							<div class="panel-body">
								<a href="#">
									<p class="heading">Sitios de Interés </p>
								</a>
								<div class="info">
									<div class="text">
										<p>Conozca todas las entidades que hacen parte del sector del Gas Natural en Colombia.</p>
									</div>
									<p class="link">
										<a href="#" class="btn btn-green">Ver todos</a>
									</p>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-6 col-xs-12">
						<?php include'content-promopods-list-panel.php'; ?>
					</div>
				</div>
			</div>
		</div>
	</section>
	
	<?php include'section-participantes.php'; ?>
	<?php include'footer.php'; ?>
</div>

<?php include'bottom.php'; ?>