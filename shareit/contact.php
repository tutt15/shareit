<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/shareit/inc/header.php'; ?>

		<div class="site-main-container">
			<!-- Start top-post Area -->
			<section class="top-post-area pt-10">
				<div class="container no-padding">
					<div class="row">
						<div class="col-lg-12">
							<div class="hero-nav-area">
								<h1 class="text-white">Liên hệ</h1>
								<p class="text-white link-nav"><a href="/">Home </a>  <span class="lnr lnr-arrow-right"></span><a href="/lien-he">Liên hệ</a></p>
							</div>
						</div>
					</div>
				</div>
			</section>
			<!-- End top-post Area -->
			<!-- Start contact-page Area -->
			<section class="contact-page-area pt-50 pb-120">
				<div class="container">
					<div class="row contact-wrap">
						<!-- <div class="map-wrap" style="width:100%; height: 445px;" id="map"></div> -->
						<div class="col-lg-3 d-flex flex-column address-wrap">
							<div class="single-contact-address d-flex flex-row">
								<div class="icon">
									<span class="lnr lnr-home"></span>
								</div>
								<div class="contact-details">
									<h5>Da Nang, Viet Nam</h5>
									<p>
										Nguyen Van Thoai
									</p>
								</div>
							</div>
							<div class="single-contact-address d-flex flex-row">
								<div class="icon">
									<span class="lnr lnr-phone-handset"></span>
								</div>
								<div class="contact-details">
									<h5>0792760***</h5>
									<p>Mon to Fri 9am to 6 pm</p>
								</div>
							</div>
							<div class="single-contact-address d-flex flex-row">
								<div class="icon">
									<span class="lnr lnr-envelope"></span>
								</div>
								<div class="contact-details">
									<h5><small>buivanthanh2727@gmail.com</small></h5>
									<p>Send us your query</p>
								</div>
							</div>
						</div>
						<?php
							if(isset($_POST['submit'])){
								$error = [];
								if(empty($_SESSION['userInfo'])){
									$name = $_POST['name'];
									$email = $_POST['email'];
									
									if($name == ''){
										$error['name'] = "Tên không được để trống";
									}
									if($email == ''){
										$error['email'] = "Email không được để trống";
									}else{
										if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
											$error['email'] = "Định dạng email không đúng";
										}
									}
								}else{
									$name = $_SESSION['userInfo']['fullname'];
									$email = $_SESSION['userInfo']['email'];
								}
								$subject = $_POST['subject'];
								$message = $_POST['message'];
								if($subject == ''){
									$error['subject'] = "Chủ đề không được để trống";
								}
								if($message == ''){
									$error['message'] = "Nội dung không được để trống";
								}
								if(empty($error)){
									$query = "INSERT INTO contact(name,email,subject,message) VALUES ('$name','$email','$subject','$message')";
									$result = $mysqli->query($query);
									if($mysqli->affected_rows > 0){
										$error['contact_success'] = "Gửi liên hệ thành công";
									}else{
										$error['contact_error'] = "Gửi liên hệ thất bại";
									}
								}
							}
						?>
						<div class="col-lg-9">
							<form class="form-area contact-form text-right" action="" method="post">
								<div class="row">
									<div class="col-lg-12">
										<?php if(isset($error['contact_success'])){ ?>
                                            <p class="alert alert-success text-center"><?php echo $error['contact_success']; ?></p>
                                        <?php } ?>
										<?php if(isset($error['contact_error'])){ ?>
                                            <p class="alert alert-danger text-center"><?php echo $error['contact_error']; ?></p>
                                        <?php } ?>
									</div>
									<div class="col-lg-6">
										<?php
											if(empty($_SESSION['userInfo'])){
										?>
											<input name="name" placeholder="Tên" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter your name'" class="common-input mb-20 form-control" type="text">
											<?php if(isset($error['name'])){ ?>
												<p class="text-error"><?php echo $error['name']; ?></p>
											<?php } ?>
											<input name="email" placeholder="Email" pattern="[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{1,63}$" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter email address'" class="common-input mb-20 form-control" type="email">
											<?php if(isset($error['email'])){ ?>
												<p class="text-error"><?php echo $error['email']; ?></p>
											<?php } ?>
										<?php
											}
										?>
										<input name="subject" placeholder="Chủ đề" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter subject'" class="common-input mb-20 form-control" type="text">
										<?php if(isset($error['subject'])){ ?>
                                            <p class="text-error"><?php echo $error['subject']; ?></p>
                                        <?php } ?>
									</div>
									<?php
										if(empty($_SESSION['userInfo'])){
											$x = 'col-lg-6';
										}else{
											$x = 'col-lg-12';
										}
									?>
									<div class="<?php echo $x ?>">
										<textarea class="common-textarea form-control" name="message" placeholder="Nội dung" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter Messege'"></textarea>
										<?php if(isset($error['message'])){ ?>
                                            <p class="text-error"><?php echo $error['message']; ?></p>
                                        <?php } ?>
									</div>
									<div class="col-lg-12 mg-10">
										<div class="alert-msg" style="text-align: left;"></div>
										<button name="submit" class="primary-btn primary" style="float: right;">Gửi</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</section>
			<!-- End contact-page Area -->
		</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/shareit/inc/footer.php'; ?>