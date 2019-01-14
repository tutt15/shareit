<?php
    $cmt_id = $_POST['cmt_id'];
?>
<div id="wrapper-rep-form">
    <div class="comment-form left-margin rep-form<?php echo $cmt_id ?>">
        <h4>Trả lời</h4>
        <form>
            <div class="form-group">
                <textarea class="form-control mb-10 rep-content<?php echo $cmt_id ?>" rows="5" name="message" placeholder="Messege" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Messege'" required=""></textarea>
            </div>
            <a href="javascript:void(0)" class="primary-btn text-uppercase submit-rep" data-cmtid="<?php echo $cmt_id ?>">Trả lời</a>
        </form>
    </div>
</div>