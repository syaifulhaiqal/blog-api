@extends('layouts.app')

@section('title', 'Posts List')

@section('content')
    <h1 class="mb-4">Room Management</h1>

    <div class="card mb-4">
        <div class="card-header">
            <h5>Add new room</h5>
        </div>
        <div class="card-body">
            <form id="postForm" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Room Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Room Image</label>
                    <input type="file" class="form-control" id="image" name="image" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Add room</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Room List</h5>
        </div>
        <div class="card-body">
            <div id="postsContainer" class="row">
                <!-- Posts will be loaded here via AJAX -->
            </div>
        </div>
    </div>

    <!-- Edit Post Modal -->
    <div class="modal fade" id="editPostModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editPostForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="editPostId" name="id">
                        <div class="mb-3">
                            <label for="editName" class="form-label">Post Name</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editImage" class="form-label">Post Image</label>
                            <input type="file" class="form-control" id="editImage" name="image">
                            <div class="mt-2">
                                <small>Current Image:</small>
                                <img id="currentImage" src="" class="img-thumbnail" style="max-width: 200px; display: none;">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="editDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="editDescription" name="description" rows="3" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="updatePost()">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .post-card {
            margin-bottom: 20px;
            transition: transform 0.2s;
        }
        .post-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        .post-image {
            max-height: 200px;
            object-fit: cover;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Load posts when page loads
        $(document).ready(function() {
            loadPosts();
        });

        // Handle form submission
        $('#postForm').submit(function(e) {
            e.preventDefault();
            createPost();
        });

        // Load all posts
        function loadPosts() {
            $.ajax({
                url: '/api/posts',
                type: 'GET',
                success: function(response) {
                    let postsHtml = '';
                    if (response.posts.length === 0) {
                        postsHtml = '<div class="col-12"><p>No posts found.</p></div>';
                    } else {
                        response.posts.forEach(post => {
                            postsHtml += `
                                <div class="col-md-4">
                                    <div class="card post-card">
                                        <img src="/storage/${post.image}" class="card-img-top post-image" alt="${post.name}">
                                        <div class="card-body">
                                            <h5 class="card-title">${post.name}</h5>
                                            <p class="card-text">${post.description}</p>
                                            <div class="action-buttons">
                                                <button class="btn btn-sm btn-warning" onclick="openEditModal(${post.id})">Edit</button>
                                                <button class="btn btn-sm btn-danger" onclick="deletePost(${post.id})">Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                    }
                    $('#postsContainer').html(postsHtml);
                },
                error: function(xhr) {
                    alert('Error loading posts: ' + xhr.responseJSON.message);
                }
            });
        }

        // Create a new post
        function createPost() {
            const formData = new FormData();
            formData.append('name', $('#name').val());
            formData.append('image', $('#image')[0].files[0]);
            formData.append('description', $('#description').val());
            formData.append('_token', $('input[name="_token"]').val());

            $.ajax({
                url: '/api/posts',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#postForm')[0].reset();
                    loadPosts();
                    alert(response.message);
                },
                error: function(xhr) {
                    alert('Error creating post: ' + (xhr.responseJSON?.message || 'Something went wrong'));
                }
            });
        }

        // Open edit modal with post data
        function openEditModal(postId) {
            $.ajax({
                url: `/api/posts/${postId}`,
                type: 'GET',
                success: function(response) {
                    $('#editPostId').val(response.post.id);
                    $('#editName').val(response.post.name);
                    $('#editDescription').val(response.post.description);

                    // Show current image
                    if (response.post.image) {
                        $('#currentImage').attr('src', `/storage/${response.post.image}`).show();
                    } else {
                        $('#currentImage').hide();
                    }

                    $('#editPostModal').modal('show');
                },
                error: function(xhr) {
                    alert('Error loading post: ' + xhr.responseJSON.message);
                }
            });
        }

        // Update post
        function updatePost() {
            const formData = new FormData();
            formData.append('id', $('#editPostId').val());
            formData.append('name', $('#editName').val());
            formData.append('description', $('#editDescription').val());
            formData.append('_token', $('input[name="_token"]').val());

            // Append image only if a new one was selected
            const imageInput = $('#editImage')[0];
            if (imageInput.files.length > 0) {
                formData.append('image', imageInput.files[0]);
            }

            $.ajax({
                url: `/api/posts/${$('#editPostId').val()}`,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-HTTP-Method-Override': 'PUT'
                },
                success: function(response) {
                    $('#editPostModal').modal('hide');
                    loadPosts();
                    alert(response.message);
                },
                error: function(xhr) {
                    alert('Error updating post: ' + (xhr.responseJSON?.message || 'Something went wrong'));
                }
            });
        }

        // Delete post
        function deletePost(postId) {
            if (confirm('Are you sure you want to delete this post?')) {
                $.ajax({
                    url: `/api/posts/${postId}`,
                    type: 'DELETE',
                    data: {
                        _token: $('input[name="_token"]').val()
                    },
                    success: function(response) {
                        loadPosts();
                        alert(response.message);
                    },
                    error: function(xhr) {
                        alert('Error deleting post: ' + xhr.responseJSON.message);
                    }
                });
            }
        }
    </script>
@endpush
