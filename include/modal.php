<div id="genericModal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h4 class="modal-title" id="genericModalTitle"></h4>
            </div>
            <div class="modal-body" id="genericModalMessage">
            </div>
            <div class="modal-footer">
                <button type="button" id="genericModalOk" class="btn bttn" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>
<script>
    function modal(title, message, codeOnOk)
    {
        codeOnOk = typeof codeOnOk!="undefined" ? codeOnOk:"";
        $("#genericModalTitle").html(title);
        $("#genericModalMessage").html(message);
        $("#genericModalOk").attr("onclick", codeOnOk);
        $("#genericModal").modal("show");
    }
</script>
