<img
    src="{{ @$product->thumbnail_url ?: getImage(getFilePath('productThumbnail') . productFilePath($product, 'thumbnail'), getFileSize('productThumbnail')) }}"
    alt="{{ @$product->title ?: trans('Product Thumbnail') }}"
    class="product_thumbnail"
/>
