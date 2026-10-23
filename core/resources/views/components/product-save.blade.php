<a href="{{ auth()->user() ? '#' : route('user.login') }}"
   class="collection-list__button wishlist-btn @auth toggle-fav-button @endauth {{ isFavorite($product->id) ? 'wishlisted' : '' }}"
   data-product-id="{{ $product->id }}" data-bs-toggle="tooltip" data-bs-placement="top"
   data-bs-title="@lang('Toggle Favorite')" data-route="{{ route('user.author.favorites.toggle') }}">
    <i class="icon-Favaret"></i>
</a>
