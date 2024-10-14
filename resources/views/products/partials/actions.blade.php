<!-- resources/views/products/partials/actions.blade.php -->

<a href="{{ route('products.show', $product->id) }}" class="btn btn-info">View</a>
<a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning">Edit</a>
<form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger">Delete</button>
</form>