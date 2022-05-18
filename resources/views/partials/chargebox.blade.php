<div class="row" id="charge">
    <div class="col-lg-6">
        <div class="modall">
            <div class="card donate mr-4">

                <h5 class="card-title mr-4">

                    <i class="fas fa-donate"></i>
                    شارژ

                    <button type="button" class="btnclose btn-close damnYouButton" style="position: relative;right: 80%;top: 0%;" data-bs-dismiss="modal"
                            aria-label="Close"></button>

                </h5>

                <div class="card-text mr-4">
                    <p class="mr-4">
                        مبلغ مورد نظر جهت افزایش موجودی کیف پول خود وارد کنید
                    </p>

                    <form action="{{route('charge')}}" method="post">
                        @csrf
                        <input type="number" name="amount" id="amount" class="form-control"
                               placeholder="... میزان شارژ حساب را وارد کنید">
                        <small class="form-error">مبلغ شارژ حداقل باید ۱۰۰۰ تومان باشد.</small>

                        <button type="submit" class="blue-btn mt-3">شارژ کن!</button>
                    </form>
                </div>

                <div id="payment_success" class="success-overlay">
                    <div class="content">
                        <i class="fas fa-smile-wink"></i>
                        <h4>مرسی!</h4>
                        <p>براش فرستادیم!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
