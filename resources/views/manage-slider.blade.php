@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Manage Slider</h1>
            <div class="form-group">
                <input type="text" id="search" class="form-control" placeholder="Search for images">
                <button id="searchBtn" class="btn btn-primary mt-2">Search</button>
            </div>

            <div id="imageResults" class="row mt-3"></div>

            <button id="saveBtn" class="btn btn-success mt-3">Save Selected Images</button>

            <h2 class="mt-5">Saved Images</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($images as $image)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><img src="{{ $image->image_url }}" alt="Image" width="50"></td>
                        <td><button class="btn btn-danger deleteBtn" data-id="{{ $image->id }}">Delete</button></td>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        // Search for images
        $('#searchBtn').click(function() {
            var query = $('#search').val();
            $.ajax({
                url: '/search-images',
                method: 'GET',
                data: { keyword: query },
                success: function(response) {
                    $('#imageResults').empty();
                    var images = response.images;
                    var imageHtml = '';

                    images.forEach(function(image) {
                        imageHtml += `
                            <div class="col-md-3">
                                <div class="card mb-3">
                                    <img src="${image.assets.preview.url}" class="card-img-top" alt="${image.description}">
                                    <div class="card-body text-center">
                                        <input type="checkbox" class="selectImage" value="${image.assets.preview.url}">
                                    </div>
                                </div>
                            </div>
                        `;
                    });

                    $('#imageResults').html(imageHtml);
                },
                error: function() {
                    alert('Failed to retrieve images.');
                }
            });
        });

        // Save selected images
        $('#saveBtn').click(function() {
            var selectedImages = [];
            $('.selectImage:checked').each(function() {
                selectedImages.push($(this).val());
            });

            if (selectedImages.length > 0) {
                $.ajax({
                    url: '/save-images',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        images: selectedImages
                    },
                    success: function(response) {
                        alert('Images saved successfully!');
                        location.reload(); // Reload the page to update saved images list
                    },
                    error: function() {
                        alert('Failed to save images.');
                    }
                });
            } else {
                alert('Please select at least one image to save.');
            }
        });

        // Delete image
        $('.deleteBtn').click(function() {
            var id = $(this).data('id');
            if (confirm('Are you sure you want to delete this image?')) {
                $.ajax({
                    url: '/delete-image/' + id,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert('Image deleted successfully!');
                        location.reload(); // Reload the page to update the table
                    },
                    error: function() {
                        alert('Failed to delete image.');
                    }
                });
            }
        });
    });
</script>
@endsection
