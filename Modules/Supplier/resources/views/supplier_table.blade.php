<div class="table-responsive">
    <table id="salesTable" class="table table-striped table-hover align-middle">
        <thead class="border-0 shadow-lg">
            <tr>
                <th>Sl</th>
                <th>Phone</th>
                <th>Name</th>
                <th>Created</th>
                <th>Date</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($purchases as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->supplier->phone}}</td>
                <td>{{ $item->supplier->name }}</td>
                <td>{{ $item->created_at->diffForHumans() }}</td>
                <td>{{ $item->created_at->format('d-M-Y') }}</td>
                <td class="text-center">
                    <div >
                        <button type="button" class="btn btn-sm border-0 dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.purchase.show', $item->id) }}">
                                    <i class="fa-solid fa-eye me-2 text-primary"></i> View
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.purchase.edit', $item->id) }}">
                                    <i class="fa-solid fa-pen-to-square me-2 text-warning"></i> Edit
                                </a>
                            </li>
                            <li>
                                <button class="dropdown-item align-items-center" onclick="delete_data({{ $item->id }})">
                                    <i class="fa-solid fa-trash me-2 text-danger"></i>Delete</button>
                            </li>
                            <form action="{{ route('admin.purchase.destroy', $item->id) }}" method="DELETE" id="delete-form-{{ $item->id }}" class="d-none">
                                @csrf
                                @method("DELETE")
                            </form>
                        </ul>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-danger">
                    No Invoice found!
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination Links -->
<div id="paginationLinks" class="mt-3 d-flex justify-content-center">
</div>

