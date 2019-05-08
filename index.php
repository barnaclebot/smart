<html>
 <head>
 <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="https://raw.githubusercontent.com/muhrizky/Smart-Parkir/master/parking_meter__2__Mrq_icon.ico">

    <title>Data Hewan Jurug Zoo</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/starter-template/">

    <!-- Bootstrap core CSS -->
    <link href="https://getbootstrap.com/docs/4.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="starter-template.css" rel="stylesheet">
  </head>
 <body>
 <nav class="navbar navbar-expand-md navbar-light fixed-top" style="background-color: #e3f2fd;">
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarsExampleDefault">
			<ul class="navbar-nav mr-auto">
			<li class="nav-item active">
				<a class="nav-link" href="https://webappexample.azurewebsites.net/">Home</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="https://webappexample.azurewebsites.net/analyze.php">Analisa Hewan<span class="sr-only">(current)</span></a>
			</li>
		</div>
		</nav>

    <main role="main" class="container">
    <div class="starter-template"> <br><br><br>
        <h1>Sistem Data Hewan Jurug Zoo</h1>
        <p class="lead">Isi data hewan sesuai form berikut ini: </p><br>
        <span class="border-top my-3"></span>
      </div>
        <form action="index.php" method="POST">
          <div class="form-group">
            <label for="name">Nama Hewan: </label>
            <input type="text" class="form-control" name="nama" id="nama" required="" >
        </div>
        <div class="form-group">
            <label for="nama_ilmiah">Nama Ilmiah </label>
            <input type="text" class="form-control" name="nama_ilmiah" id="nama_ilmiah" required="">
        </div>
        <div class="form-group">
            <label for="kelas">Kelas </label>
            <input type="text" class="form-control" name="kelas" id="kelas" required="">
        </div>
        <div class="mt-4 mb-2">
			<form class="d-flex justify-content-lefr" action="index.php" method="post" enctype="multipart/form-data">
				<input type="file" name="fileToUpload" accept=".jpeg,.jpg,.png" required="">
            </form>
        </div>
            <input type="submit" class="btn btn-success" name="submit" value="Submit Data Kendaraan" action="index.php" method="post" enctype="multipart/form-data">
        </form>
        <!-- <br><br> -->
        <form action="index.php" method="GET">
          <div class="form-group">
            <input type="submit" class="btn btn-info" name="load_data" value="Lihat Data Yang Sudah Registrasi">
          </div>
        </form>   
   
 <?php
     //blobs
     require_once 'vendor/autoload.php';
     require_once "./random_string.php";
 
     use MicrosoftAzure\Storage\Blob\BlobRestProxy;
     use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
     use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
     use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
     use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;
 
     $connectionString = "DefaultEndpointsProtocol=https;AccountName=examplestorage23;AccountKey=/mpKAIDEXW6fUu3YOv5MskbO3d7OvxHNP2gNdLlhFJTJoqkgmImc26Oa2sexpAu4Gpp1wYMacq4iITsP7xm6Tw==;";
     $containerName = "example";
     // Create blob client.
     $blobClient = BlobRestProxy::createBlobService($connectionString);
     if (isset($_POST['submit'])) {
         $fileToUpload = strtolower($_FILES["fileToUpload"]["name"]);
         $content = fopen($_FILES["fileToUpload"]["tmp_name"], "r");
         // echo fread($content, filesize($fileToUpload));
         $blobClient->createBlockBlob($containerName, $fileToUpload, $content);
         header("Location: index.php");
     }
     $listBlobsOptions = new ListBlobsOptions();
     $listBlobsOptions->setPrefix("");
     $result = $blobClient->listBlobs($containerName, $listBlobsOptions);


     //SQL
    $host = "example23.database.windows.net";
    $user = "barnaclebot";
    $pass = "Kerumitan23";
    $db = "smart";

    try {
        $conn = new PDO("sqlsrv:server = $host; Database = $db", $user, $pass);
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    } catch(Exception $e) {
        echo "Failed: " . $e;
    }

    if (isset($_POST['submit'])) {
        try {
            $name = $_POST['nama'];
            $nama_ilmiah = $_POST['nama_ilmiah'];
            $kelas = $_POST['kelas'];
            $date = date("Y-m-d");
            // Insert data
            $sql_insert = "INSERT INTO Hewan (nama, nama_ilmiah, kelas, date) 
                        VALUES (?,?,?,?)";
            $stmt = $conn->prepare($sql_insert);
            $stmt->bindValue(1, $nama);
            $stmt->bindValue(2, $nama_ilmiah);
            $stmt->bindValue(3, $kelas);
            $stmt->bindValue(4, $date);
            $stmt->execute();
        } catch(Exception $e) {
            echo "Failed: " . $e;
        }

        echo "<h3>Your're registered!</h3>";
    } else if (isset($_GET['load_data'])) {
        try {
            $sql_select = "SELECT * FROM Hewan";
            $stmt = $conn->query($sql_select);
            $registrants = $stmt->fetchAll(); 
            if(count($registrants) > 0) {
                echo "<h2>Jumlah Hewan : ".count($registrants)."</h2>";
                echo "<table class='table table-hover'><thead>";
                echo "<tr><th>Nama Hewan</th>";
                echo "<th>Nama Ilmiah</th>";
                echo "<th>Kelas</th>";
                echo "<th>Data Record</th></tr></thead><tbody>";
                foreach($registrants as $registrant) {
                    echo "<tr><td>".$registrant['nama']."</td>";
                    echo "<td>".$registrant['nama_ilmiah']."</td>";
                    echo "<td>".$registrant['kelas']."</td>";
                    echo "<td>".$registrant['date']."</td></tr>";
                }
                echo "</tbody></table>";
                echo "<h4>Total Files : ".sizeof($result->getBlobs())."/h4";
                echo "<table class='table table-hover'><thead>";
                echo "<tr><th>File Name</th>";
                echo "<th>File URL</th>";
                echo "<th>Action</th></tr></thead><tbody>";
                do {
					foreach ($result->getBlobs() as $blob)
					{
						echo "<tr>";
							echo "<td>".$blob->getName()."</td>";
							echo "<td>".$blob->getUrl()."</td>";
							echo "<td>";
								echo "<form action='computervision.php' method='post'>";
								echo "<input type='hidden' name='url' value='$blob->getUrl()'>";
								echo "<input type='submit' name='submit' value='Analyze!' class='btn btn-primary'>";
								echo "</form>";
							echo "</td>";
						echo "</tr>";
					}
					$listBlobsOptions->setContinuationToken($result->getContinuationToken());
				} while($result->getContinuationToken());


            } else {
                echo "<h3>No one is currently registered.</h3>";
            }
        } catch(Exception $e) {
            echo "Failed: " . $e;
        }
    }

   
 ?>
 </div>
    </main><!-- /.container -->

</tbody>
</table>
 
<!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
    <script src="https://getbootstrap.com/docs/4.0/assets/js/vendor/popper.min.js"></script>
    <script src="https://getbootstrap.com/docs/4.0/dist/js/bootstrap.min.js"></script>
  </body>
</html>