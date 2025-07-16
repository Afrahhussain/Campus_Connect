<div class="bg-blue-600 text-white p-4 flex justify-between items-center">
  <h1 class="text-xl font-semibold">Dashboard</h1>
  <div>
    Logged in as: <strong><?= $_SESSION['user']['name'] ?> (<?= ucfirst($_SESSION['user']['role']) ?>)</strong>
    | <a href="../../logout.php" class="underline text-white ml-4">Logout</a>
  </div>
</div>
