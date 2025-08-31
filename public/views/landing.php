<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Blog</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;600&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/style.css">
    <script>
		document.addEventListener('DOMContentLoaded', () => {

			const exploreBtn = document.querySelector('.explore-btn');
			if(exploreBtn){
				exploreBtn.addEventListener('click', function(e){
					e.preventDefault();
					window.location.href = "index.php?page=blog&action=get";
				});
			}

		});
    </script>
</head>
<body>
    <header class="navbar">
        <nav>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="index.php?page=auth&action=login"><button class="login-btn">Login</button></a></li>
            </ul>
        </nav>
    </header>

    <section class="hero">
      <div class="hero-image">
        <img src="https://t3.ftcdn.net/jpg/05/49/37/30/360_F_549373036_sjqy4Y3BUfFKAAELVfvOw0gIDAZ6QmH6.jpg" alt="Hero Travel">
        <!-- <h1>Blog your best<br>moment</h1> -->
        <div class="hero-text">
            <img src="../assets/images/Blog_head.png">
        </div>
      </div>
    </section>

    <section class="below-hero">
      <div class="below-left">
        <p>“Because every road has a story,<br>
          every ride holds a memory,<br>
          and every journey leaves us a little different than we were before.”</p>
      </div>
      <div class="below-right">
        <img src="../assets/images/img1.png" alt="Travel 2">
        <img src="../assets/images/img2.png" alt="Travel 3">
        <img src="../assets/images/img3.png" alt="Travel 4">
      </div>
    </section>

    <section class="blog-section">
      <div class="blog-cards">
        <div class="blog-card">
          <img src="data:image/jpeg;base64,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" alt="Hills">
          <h3>Riding Through the Hills</h3>
          <p>Experience the thrill of winding mountain roads and breathtaking views...</p>
        </div>
        <div class="blog-card second-card">
          <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSWX-UZkIvXPputT9G8SVa8fFqlm86HIOndHw&s" alt="City">
          <h3>Night Lights</h3>
          <p>Discover the magic of the city that never sleeps...</p>
        </div>
      </div>

      <div class="blog-right">
        <img src="../assets/images/Blog_head2.png" alt="On The Blog" class="blog-title-img">
        <button class="explore-btn">Explore Blogs</button>
      </div><br><br>
    </section>

    <section class="about-us">
      <div class="about-container">
        <img src="../assets/images/Blog_head3.png" alt="About us" style="height: 150px;">
        <p>
          We are passionate explorers, storytellers, and creators who love sharing
          unique travel experiences, hidden gems, and inspiring stories.  
          Our goal is to spark your curiosity, fuel your adventures, and
          bring you closer to the world around you.
        </p>
      </div>
    </section>

    <footer class="footer">
      <div class="footer-container">
        <div class="footer-about">
          <h3>Travelogue</h3>
          <p>Inspiring journeys & untold stories.</p>
        </div>
        <div class="footer-links">
          <h4>Quick Links</h4>
          <ul>
            <li><a href="?page=home&action=get">Home</a></li>
            <li><a href="?page=blog&action=get">Blogs</a></li>
          </ul>
        </div>
        <div class="footer-social">
          <h4>Follow Us</h4>
          <a href="#">🌍</a>
          <a href="#">📸</a>
          <a href="#">🐦</a>
        </div>
      </div>
      <div class="footer-bottom">
        <p>© 2025 Travelogue. All Rights Reserved.</p>
      </div>
    </footer>



</body>
</html>
