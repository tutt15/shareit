<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/shareit/inc/header.php'; ?>
<?php
    if(isset($_GET['s'])){
        $search = $_GET['s']; 
    }else{
        header('Location:/');
    }
    $queryTSD = "SELECT COUNT(*) AS TSD FROM news INNER JOIN user ON news.created_by = user.id WHERE (name LIKE '%$search%' OR detail LIKE '%$search%') AND news.active = 1 AND user.active = 1";
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
					<div class="row">
						<div class="col-lg-12">
							<div class="hero-nav-area">
								<h1 class="text-white">Tìm kiếm</h1>
								<p class="text-white link-nav"><a href="/">Home </a>  <span class="lnr lnr-arrow-right"></span>Tìm kiếm</p>
							</div>
						</div>
					</div>
				</div>
			</section>
			<!-- End top-post Area -->
			<!-- Start latest-post Area -->
			<section class="latest-post-area pb-120">
				<div class="container no-padding">
					<div class="row">
						<div class="col-lg-8 post-list">
							<!-- Start latest-post Area -->
							<div class="latest-post-wrap">
								<h4 class="cat-title">Kết quả</h4>
								<?php
									$querySltNews = "SELECT news.id AS news_id, picture, news.name AS news_name, fullname, date_create, view, preview FROM news INNER JOIN user ON news.created_by = user.id WHERE (name LIKE '%$search%' OR detail LIKE '%$search%') AND user.active = 1 AND news.active = 1 ORDER BY news.id DESC LIMIT $offset, $row_count";
									$resultSltNews = $mysqli->query($querySltNews);
									while($arNews = $resultSltNews->fetch_assoc()){
										$news_id = $arNews['news_id'];
										$news_picture = $arNews['picture'];
										$news_name = $arNews['news_name'];
										$user_fullname = $arNews['fullname'];
										$news_date_create = date( "d/m/Y", strtotime($arNews['date_create']));
										$news_view = $arNews['view'];
										$news_preview = $arNews['preview'];
										$urlSlugDetail = '/chi-tiet/'.utf8ToLatin($news_name).'-'.$news_id.'.html';
								?>
								<div class="single-latest-post row align-items-center">
									<div class="col-lg-5 post-left">
										<div class="feature-img relative">
											<div class="overlay overlay-bg"></div>
											<a href="<?php echo $urlSlugDetail ?>">
												<?php
													if($news_picture == ''){
												?>
												<img class="img-fluid" src="/files/white-picture.png" alt="" width="280" height="190">
												<?php
													}else{
												?>
												<img class="img-fluid" src="/files/<?php echo $news_picture ?>" alt="" width="280" height="190">
												<?php
													}
												?>
											</a>
										</div>
									</div>
									<div class="col-lg-7 post-right">
										<a href="<?php echo $urlSlugDetail ?>">
											<h4><?php echo $news_name ?></h4>
										</a>
										<ul class="meta">
											<li><a href=""><span class="lnr lnr-user"></span><?php echo $user_fullname ?></a></li>
											<li><span class="lnr lnr-calendar-full"></span><?php echo $news_date_create ?></li>
											<li><span class="lnr lnr-bubble"></span><?php echo $news_view ?></li>
										</ul>
										<p class="excert">
											<?php echo $news_preview ?>
										</p>
									</div>
								</div>
								<?php
									}
								?>
								<?php
									if($tongSoTrang > 1){
								?>
								<div class="pagination">
									<ul>
										<?php 
											$urlSlugPagIndex = '/search.php?s='.$search.'&page=';
											// $urlSlugPagIndex = '/tim-kiem/?s='.$search.'/trang-';
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
								<?php
									}
								?>
							</div>
							<!-- End latest-post Area -->
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