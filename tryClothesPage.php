<?php
   include('session.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Virtual Try-On</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background: linear-gradient(135deg, #00ff00, #008000);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    #tryon-section {
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    canvas {
      border: 1px solid #ccc;
    }
    #controls {
      text-align: center;
      margin-top: 20px;
    }
    button {
      padding: 10px 20px;
      font-size: 16px;
      cursor: pointer;
    }
    .search-container {
      margin-top: 20px;
      text-align: center;
    }
    .search-box {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      width: 300px;
      max-width: 100%;
    }
    .search-button {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div id="tryon-section">
    <h1 style="text-align: center;">Virtual Try-On</h1>

    <div class="search-container">
        <form action="/search" method="get">
            <input type="text" name="q" placeholder="Search..." class="search-box">
            <button type="submit" class="search-button">Search</button>
        </form>
    </div>

    <div id="canvas-container">
      <canvas id="virtual-tryon-canvas" width="600" height="400"></canvas>
    </div>

    <div id="controls">
      <button onclick="changeClothes('shirt')">Change Shirt</button>
      <button onclick="changeClothes('pants')">Change Pants</button>
    </div>
  </div>

  <script>
    const canvas = document.getElementById('virtual-tryon-canvas');
    const ctx = canvas.getContext('2d');

    let currentClothes = 'shirt'; // Initial clothes

    function drawClothes(type) {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      // Draw clothes based on type
      if (type === 'shirt') {
        // Drawing a shirt (example)
        ctx.fillStyle = 'blue';
        ctx.fillRect(200, 100, 200, 200);
      } else if (type === 'pants') {
        // Drawing pants (example)
        ctx.fillStyle = 'green';
        ctx.fillRect(200, 200, 200, 200);
      }
    }

    function changeClothes(type) {
      currentClothes = type;
      drawClothes(type);
    }

    // Initial draw
    drawClothes(currentClothes);
  </script>
</body>
</html>
