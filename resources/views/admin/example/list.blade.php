<!-- Examples List -->
@if (count($examples) > 0)
    <div class="panel panel-default">
        <div class="panel-heading">
            Examples List
        </div>

        <div class="panel-body">
            <table class="table table-striped">
                <!-- Table Headings -->
                <thead>
                    <th>Name</th>
                    <th>&nbsp;</th>
                </thead>

                <!-- Table Body -->
                <tbody>
                    @foreach ($examples as $example)
                        <tr>
                            <!-- Example Name -->
                            <td class="table-text">
                                <div>{{ $example->name }}</div>
                            </td>

                            <!-- Delete Button -->
                            <td>
                                <form action="{{ route('admin::example.destroy', [$example->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" id="delete-example-{{ $example->id }}" class="btn btn-danger">
                                        <i class="fa fa-btn fa-trash"></i>Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
