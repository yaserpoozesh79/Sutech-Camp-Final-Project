<script>
    var openUpgradeAfterCharge = false;
    function modalfunc(value) {
        $("#upgrade_account").slideUp(500);
        $(".modall").slideDown(500);
        openUpgradeAfterCharge = value;
    }
    $(".btnclose").click(function () {
        if(openUpgradeAfterCharge){
            $(".modall").slideUp(500);
            $("#upgrade_account").slideDown(500);
        }else
            $(".modall").slideUp(500);
        openUpgradeAfterCharge = false;
    });
</script>
