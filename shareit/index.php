<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/shareit/inc/header.php'; ?>

<?php
    $queryTSD = "SELECT COUNT(*) AS TSD FROM news INNER JOIN user ON news.created_by = user.id WHERE news.active = 1 AND user.active = 1";
    $ressultTSD = $mysqli->query($queryTSD);
    $arTmp = $ressultTSD->fetch_assoc();
    $tongSoDong = $arTmp['TSD'];
    $row_count = 10;
    $tongSoTrang = ceil($tongSoDong / $row_count);
    $current_page = 1;
    if(isset($_GET['page'])){
        if($_GET['page'] > $tongSoTrang || $_GET['page'] < 1 || !preg_match("/^[0-9]+$/", $_GET['page'])){
            header('Location:/');
            die();
        }
        $current_page = $_GET['page'];
    }
	$offset = ($current_page - 1) * $row_count;
?>

		<div class="site-main-container">
			<!-- Start top-post Area -->
			<section class="top-post-area pt-10">
				<div class="container no-padding">
					<div class="row small-gutters">
						<?php
							$querySltOneNews = "SELECT news.id AS news_id, picture, news.name AS news_name, fullname, date_create, view FROM news INNER JOIN user ON news.created_by = user.id WHERE news.active = 1 AND user.active AND user.role = 'admin' ORDER BY news.id DESC LIMIT 0,1";
							$resultSltOneNews = $mysqli->query($querySltOneNews);
							$oneNews = $resultSltOneNews->fetch_assoc();
							$news_id = $oneNews['news_id'];
							$news_name = $oneNews['news_name'];
							$urlSlugDetail = '/chi-tiet/'.utf8ToLatin($news_name).'-'.$news_id.'.html';
						?>
						<div class="col-lg-8 top-post-left">
							<div class="feature-image-thumb relative">
								<div class="overlay overlay-bg"></div>
								<?php
									if($oneNews['picture'] == ''){
								?>
								<img class="img-fluid" src="/files/white-picture.png" alt="" width="756" height="440">
								<?php
									}else{
								?>
								<img class="img-fluid" src="/files/<?php echo $oneNews['picture'] ?>" alt="" width="756" height="440">
								<?php
									}
								?>
							</div>
							<div class="top-post-details">
								<a href="<?php echo $urlSlugDetail ?>">
									<h3><?php echo $oneNews['news_name'] ?></h3>
								</a>
								<ul class="meta">
									<li><a href="#"><span class="lnr lnr-user"></span><?php echo $oneNews['fullname'] ?></a></li>
									<li><span class="lnr lnr-calendar-full"></span><?php echo date( "d/m/Y", strtotime($oneNews['date_create'])) ?></li>
									<li><span class="lnr lnr-eye"></span><?php echo $oneNews['view'] ?> Views</li>
								</ul>
							</div>
						</div>
						<div class="col-lg-4 top-post-right">
							<?php
								$querySltTwoNews = "SELECT news.id AS news_id, picture, news.name AS news_name, fullname, date_create, view FROM news INNER JOIN user ON news.created_by = user.id WHERE news.active = 1 AND user.active AND user.role = 'admin' ORDER BY news.id DESC LIMIT 1,2";
								$resultSltTwoNews = $mysqli->query($querySltTwoNews);
								while($twoNews = $resultSltTwoNews->fetch_assoc()){
									$news_id = $twoNews['news_id'];
									$news_name = $twoNews['news_name'];
									$urlSlugDetail = '/chi-tiet/'.utf8ToLatin($news_name).'-'.$news_id.'.html';
							?>
							<div class="single-top-post">
								<div class="feature-image-thumb relative">
									<div class="overlay overlay-bg"></div>
									<?php
										if($twoNews['picture'] == ''){
									?>
									<img class="img-fluid" src="/files/white-picture.png" alt="" width="370" height="215">
									<?php
										}else{
									?>
									<img class="img-fluid" src="/files/<?php echo $twoNews['picture'] ?>" alt="" width="370" height="215" >
									<?php
										}
									?>
								</div>
								<div class="top-post-details">
									<a href="<?php echo $urlSlugDetail ?>">
										<h4><?php echo $twoNews['news_name'] ?></h4>
									</a>
									<ul class="meta">
										<li><a href="#"><span class="lnr lnr-user"></span><?php echo $twoNews['fullname'] ?></a></li>
										<li><span class="lnr lnr-calendar-full"></span><?php echo date( "d/m/Y", strtotime($twoNews['date_create'])) ?></li>
										<li><span class="lnr lnr-bubble"></span><?php echo $twoNews['view'] ?> Views</li>
									</ul>
								</div>
							</div>
							<?php
								}
							?>
						</div>
						<!-- <div class="col-lg-12">
							<div class="news-tracker-wrap">
								<h6><span>Breaking News:</span>   <a href="#">Astronomy Binoculars A Great Alternative</a></h6>
							</div>
						</div> -->
					</div>
				</div>
			</section>
			<!-- End top-post Area -->
			<!-- Start latest-post Area -->
			<section class="latest-post-area pb-80">
				<div class="container no-padding">
					<div class="row">
						<div class="col-lg-8 post-list">
							<!-- Start latest-post Area -->
							<div class="latest-post-wrap">
								<h4 class="cat-title">Tin mới</h4>
								<?php
									$querySltNews = "SELECT news.id AS news_id, picture, news.name AS news_name, fullname, date_create, view, preview FROM news INNER JOIN user ON news.created_by = user.id WHERE news.active = 1 AND user.active ORDER BY news.id DESC LIMIT {$offset},{$row_count}";
									$resultSltNews = $mysqli->query($querySltNews);
									while($arNews = $resultSltNews->fetch_assoc()){
										$news_id = $arNews['news_id'];
										$news_name = $arNews['news_name'];
										$urlSlugDetail = '/chi-tiet/'.utf8ToLatin($news_name).'-'.$news_id.'.html';
								?>
								<div class="single-latest-post row align-items-center">
									<div class="col-lg-5 post-left">
										<div class="feature-img relative">
											<div class="overlay overlay-bg"></div>
											<a href="<?php echo $urlSlugDetail ?>">
												<?php
													if($arNews['picture'] == ''){
												?>
												<img class="img-fluid" src="/files/white-picture.png" alt="" width="260" height="180">
												<?php
													}else{
												?>
												<img class="img-fluid" src="/files/<?php echo $arNews['picture'] ?>" alt="" width="260" height="180" >
												<?php
													}
												?>
											</a>
										</div>
									</div>
									<div class="col-lg-7 post-right">
										<a href="<?php echo $urlSlugDetail ?>">
											<h4><?php echo $arNews['news_name'] ?></h4>
										</a>
										<ul class="meta">
											<li><a href="#"><span class="lnr lnr-user"></span><?php echo $arNews['fullname'] ?></a></li>
											<li><a href="#"><span class="lnr lnr-calendar-full"></span><?php echo date( "d/m/Y", strtotime($arNews['date_create'])) ?></a></li>
											<li><a href="#"><span class="lnr lnr-eye"></span><?php echo $arNews['view'] ?> Views</a></li>
										</ul>
										<p class="excert">
											<?php echo $arNews['preview'] ?>
										</p>
									</div>
								</div>
								<?php
									}
								?>
								<div class="pagination">
									<ul>
										<?php 
											$urlSlugPagIndex = '/trangchu-';
											if($current_page > 1 && $tongSoTrang > 1){
										?>
										<li><a href="<?php echo $urlSlugPagIndex ?><?php echo $current_page - 1 ?>">&laquo;</a></li>
										<?php
											}
										?>
										<?php
											for($i = 1; $i <= $tongSoTrang; $i++){
												if($i == $current_page){
										?>
										<li><a class="active" href="#"><?php echo $i ?></a></li>
										<?php
											}else{
										?>
										<li><a href="<?php echo $urlSlugPagIndex ?><?php echo $i ?>"><?php echo $i ?></a></li>
										<?php            
												}
											}
										?>
										<?php
											if($current_page < $tongSoTrang && $tongSoTrang > 1){
										?>    
										<li><a href="<?php echo $urlSlugPagIndex ?><?php echo $current_page + 1 ?>">&raquo;</a></li>
										<?php
											}
										?>
									</ul>
								</div>
							</div>
							<!-- End latest-post Area -->
							
							<!-- Start banner-ads Area -->
							<!-- <div class="col-lg-12 ad-widget-wrap mt-30 mb-30">
								<img class="img-fluid" src="/templates/shareit/images/banner-ad.jpg" alt="">
							</div> -->
							<!-- End banner-ads Area -->
						</div>
						<div class="col-lg-4">
							<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/shareit/inc/rightbar.php'; ?>
						</div>
					</div>
				</div>
			</section>
			<!-- End latest-post Area -->
		</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/shareit/inc/footer.php'; ?>
		