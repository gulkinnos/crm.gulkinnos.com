<?php $this->start('head') ?>
<?php $this->end() ?>
<?php $this->start('body') ?>
<h1>Welcome to gulkinnos project</h1>
<div onclick="ajaxTest()">Click me!!!</div>

<!---->

<script>
    function ajaxTest() {
        $.ajax({
            type: "POST",
            url: '<?=PROOT?>examples/testAjax',
            data: {model_id: 45},
            success: function (response) {
                if (response.success) {
                    window.alert(response.data.name);
                }
                console.log(response);
            }
        });
    }
</script>
<?php $this->end() ?>
