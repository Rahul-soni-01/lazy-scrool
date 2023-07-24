 <!-- resources/views/images.blade.php -->
 <form action="{{ route('uploadImage') }}" method="post" enctype="multipart/form-data">
     @csrf
     <input type="file" name="image">
     <input type="text" name="caption" placeholder="Image Caption">
     <button type="submit">Upload Image</button>
 </form>
 {{--
@extends('layouts.app')
@section('content')
    <div id="image-container">
   
    </div>

    <script>
        /*  let page = 1;

            function lazyLoadImages() {
                const container = document.getElementById('image-container');
                const scrollHeight = container.scrollHeight;
                const scrollTop = container.scrollTop;
                const clientHeight = container.clientHeight;

                if (scrollHeight - scrollTop === clientHeight) {
                    fetchImages();
                }
            }

            async function fetchImages() {
                const response = await fetch(`/api/images?page=${page}`);
                const data = await response.json();

                if (data.data.length > 0) {
                    // console.log(data.data.map);
                    const images = data.data.map(image => `<img src="${image.image}" alt="${image.caption}">`);
                    const imageContainer = document.getElementById('image-container');
                    imageContainer.innerHTML += images.join('');
                    page++;
                }
            }
            fetchImages();
            const imageContainer = document.getElementById('image-container');
            imageContainer.addEventListener('scroll', lazyLoadImages);
            */
    </script>
@endsection

<script>
    const cheerio = require('cheerio');
    const imageContainer = document.getElementById('image-container');

    // Function to fetch images from the API
    async function fetchImages() {
        try {
            const response = await fetch('/api/images');
            const data = await response.json();
            console.log(data.data);
            let imagesHTML = '';

            if (data.data.length > 0) {
                imagesHTML = data.data.map(image => `<img src="${image.image}" alt="${image.caption}">`).join('');
            }
            // Load the HTML string into cheerio
            const $ = cheerio.load('<div id="image-container"></div>');

            // Append the generated images to the image-container div
            $('#image-container').append(imagesHTML);

            // Get the final HTML with the images
            const finalHTML = $.html();
        } catch (error) {
            console.error('Error fetching images:', error);
        }
    }

    // Initial fetch
    fetchImages();
</script> --}}
 <style>
     .image-style {
         height: 200px;
         width: 300px;
     }
 </style>
 <!DOCTYPE html>
 <html>

 <head>
     <title>Uploaded Images Display</title>
 </head>

 <body>
     <div id="image-container"></div>
     <script>
        // Function to fetch images from the API
        async function fetchImages() {
            try {
                const response = await fetch('/api/images');
                const data = await response.json();
                console.log(data.data);

                const imageContainer = document.getElementById('image-container');
                let imagesHTML = '';

                if (data.data.length > 0) {
                    imagesHTML = data.data.map(image => `
                        <div>F
                            <img data-src="/image/${image.image}" alt="${image.caption}" class="image-style ">
                            <p>${image.caption}</p>
                        </div>
                    `).join('');
                }

                // Append the generated images to the image-container div
                imageContainer.innerHTML = imagesHTML;

                // Lazy load images as the user scrolls
                const imagesToLazyLoad = imageContainer.querySelectorAll('img[data-src]');
                const observerOptions = {
                    rootMargin: '200px', // Load images 200px before they enter the viewport
                };
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                             const img = entry.target;
                             img.src = img.getAttribute('data-src');
                             img.removeAttribute('data-src');
                             imageObserver.unobserve(img);
                        }
                    });
                }, observerOptions);
                 imagesToLazyLoad.forEach(img => {
                     imageObserver.observe(img);
                });
            } catch (error) {
                 console.error('Error fetching images:', error);
            }
         }
         // Initial fetch
         fetchImages();
     </script>
 </body>
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <script>
     $(document).ready(function() {
         var isLoading = false;
         var currentOffset = 5;
         var lastScrollTop = 0;
         let offset = 0;
         // Number of data entries to load per request
         $(window).scroll(function() {
             if (!isLoading && $(window).scrollTop() + $(window).height() >= $(document).height() -
                 100) {
                 loadMoreData();
             }
         });

         // Function to fetch and append more data
         function loadMoreData() {
             // alert(currentOffset);
             if (!isLoading) {
                 isLoading = true;
                 $.ajax({
                     url: '/load-more-data',
                     type: 'GET',
                     data: {
                         offset: currentOffset
                     },
                     success: function(response) {
                         if (response.length > 0) {
                             response.forEach(function(dataEntry) {
                                 $('#image-container').append(
                                     `<div><img src="/image/${dataEntry.image}" alt="${dataEntry.caption}" class="image-style"><p>${dataEntry.caption}</p></div>`
                                 );
                                 isLoading = false;
                                 currentOffset += response.length;
                             })
                         }
                     },
                     error: function(xhr) {
                         isLoading = false;
                     }
                 });
             }
         }
     });
 </script>

 </html>
