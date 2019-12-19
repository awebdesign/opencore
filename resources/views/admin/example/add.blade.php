<div class="panel-body">
    <!-- New Example Form -->
    <form action="{{ route('admin::example.store') }}" method="POST" class="form-horizontal">
        @csrf

        <!-- Example Name -->
        <div class="form-group">
            <label for="example-name" class="col-sm-3 control-label">Example</label>

            <div class="col-sm-6">
                <input type="text" name="name" id="example-name" class="form-control">
            </div>
        </div>

        <!-- Add Example Button -->
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-6">
                <button type="submit" class="btn btn-default">
                    <i class="fa fa-plus"></i> Add Example
                </button>
            </div>
        </div>
    </form>
</div>
