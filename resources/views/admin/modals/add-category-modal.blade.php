<div class="modal fade" id="add-category-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form role="form" class="form-inline" action="{{ route('category.store') }}" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">NEW KATEGORI</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="name" class="form-control-label">NAMA KATEGORI</label>
                        <input id="name" type="text" class="form-control ml-3" name="name" autofocus>
                    </div>
                    <div class="form-group mb-3">
                        <label for="description" class="form-control-label">DESC KATEGORI</label>
                        <textarea id="description" class="autosize-target form-control ml-3" name="description"></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="cover_img" class="form-control-label">FOTO</label>
                        <input id="cover_img" type="text" class="form-control ml-3" name="cover_img">
                    </div>
                    {{ csrf_field() }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">BATAL</button>
                    <button id="confirm-btn" type="submit" class="btn btn-primary">SUBMIT</button>
                </div>
            </div><!-- /.modal-content -->
        </form>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->