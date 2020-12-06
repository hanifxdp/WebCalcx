<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Columnar -- WebCalcx</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous" />
</head>

<body>
  <?php
  class Pair
  {
    public $Key;
    public $Value;
  }

  function compare($val1, $val2)
  {
    return strcmp($val1->Value, $val2->Value);
  }

  function ShiftIndexes($key)
  {
    $lenOfkey = strlen($key);
    $indexes = array();
    $sortedarray = array();
    //make a pair using the key 
    for ($i = 0; $i < $lenOfkey; ++$i) {
      $pair = new Pair();
      $pair->Key = $i;
      $pair->Value = $key[$i];
      $sortedarray[] = $pair;
    }
    //sort Sortedarray 
    usort($sortedarray, 'compare');

    // return thr postions of the sorted key 
    for ($i = 0; $i < $lenOfkey; ++$i)
      $indexes[$sortedarray[$i]->Key] = $i;

    return $indexes;
  }

  function Encrypt2($text, $key)
  {
    $lenoftext = strlen($text);
    $lenOfkey = strlen($key);
    // adjust the text replace every space with (-)
    $text = ($lenoftext % $lenOfkey == 0) ? $text : str_pad($text, $lenoftext - ($lenoftext % $lenOfkey) + $lenOfkey, "-", STR_PAD_RIGHT);
    $lenoftext = strlen($text);
    $numofcols = $lenOfkey;
    $numofrows = ceil($lenoftext / $numofcols);
    $rowmatrix1 = array(array());
    $colmatrix2 = array(array());
    $sortedcolmatrix2 = array(array());
    $shiftIndexes = ShiftIndexes($key);

    for ($i = 0; $i < $lenoftext; ++$i) {
      $currentRow = $i / $numofcols;
      $currentColumn = $i % $numofcols;
      $rowmatrix1[$currentRow][$currentColumn] = $text[$i];
    }

    for ($i = 0; $i < $numofrows; $i++) {
      for ($j = 0; $j < $numofcols; $j++) {
        $colmatrix2[$j][$i] = $rowmatrix1[$i][$j];
      }
    }
    for ($i = 0; $i < $numofcols; $i++) {
      for ($j = 0; $j < $numofrows; $j++) {
        $sortedcolmatrix2[$shiftIndexes[$i]][$j] = $colmatrix2[$i][$j];
      }
    }
    $ciphertext = "";
    for ($i = 0; $i < $lenoftext; $i++) {
      $currentRow = $i / $numofrows;
      $currentColumn = $i % $numofrows;
      $ciphertext .= $sortedcolmatrix2[$currentRow][$currentColumn];
    }

    return $ciphertext;
  }

  function Decrypt2($text, $key)
  {

    $lenOfkey = strlen($key);
    $lenoftext = strlen($text);
    $numofcols = ceil($lenoftext / $lenOfkey);
    $numofrows = $lenOfkey;
    $rowmatrix1 = array(array());
    $colmatrix2 = array(array());
    $unsortedcolmatrix2 = array(array());
    $shiftIndexes = ShiftIndexes($key);

    for ($i = 0; $i < $lenoftext; ++$i) {
      $currentRow = $i / $numofcols;
      $currentColumn = $i % $numofcols;
      $rowmatrix1[$currentRow][$currentColumn] = $text[$i];
    }

    for ($i = 0; $i < $numofrows; $i++) {
      for ($j = 0; $j < $numofcols; $j++) {
        $colmatrix2[$j][$i] = $rowmatrix1[$i][$j];
      }
    }

    for ($i = 0; $i < $numofcols; $i++) {
      for ($j = 0; $j < $numofrows; $j++) {
        $unsortedcolmatrix2[$i][$j] = $colmatrix2[$i][$shiftIndexes[$j]];
      }
    }
    $plaintext = "";
    for ($i = 0; $i < $lenoftext; $i++) {
      $currentRow = $i / $numofrows;
      $currentColumn = $i % $numofrows;
      $plaintext .= $unsortedcolmatrix2[$currentRow][$currentColumn];
    }

    return $plaintext;
  }

  $hasil = "";
  if (isset($_POST['submit'])) {
    $teks  = $_POST["teks"];
    $kunci  = $_POST["kunci"];

    // $teks = $_GET['teks'];
    // $kunci = $_GET['kunci'];
    $operator = $_POST['operator'];

    switch ($operator) {
      case "Encrypt":
        $hasil = Encrypt2($teks, $kunci);
        break;
      case "Decrypt":
        $hasil = Decrypt2(Encrypt2($teks, $kunci), $kunci);
        break;
      default:
        echo "You should to select a method!";
        break;
    }
  }



  ?>

  <div class="container">
    <div class="row">
      <div class="col">
        <div class="shadow-lg p-3 mb-5 bg-white rounded">
          <blockquote class="blockquote text-center">
            <h1>Web Calcx Cryptography</h1>
            <footer class="blockquote-footer">Columnar and Diagonal</footer>
          </blockquote>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-5">
        <h2>Columar Cipher.</h2>
      </div>
    </div>
    <div class="row">
      <div class="col-md-5">
        <p>
          melibatkan penulisan teks biasa dalam baris, dan kemudian membaca
          teks tersandi dalam kolom satu per satu.
        </p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <form action="" method="POST">
          <div class="form-group">
            <label for="plaintext-ciphertext">Plaintext/Ciphertext</label>
            <input type="text" class="form-control" name="teks" />
            <small id="texthelp" class="form-text text-muted">Insert PT/CT.</small>
          </div>
          <div class="form-group">
            <label for="keygen">Key</label>
            <input type="text" class="form-control" name="kunci" />
            <small id=" keygenhelp" class="form-text text-muted">Insert Key.</small>
          </div>

          <label for="dropdown-menu">Method</label>
          <select name="operator" class="custom-select custom-select-sm mb-4">
            <option selected>Method</option>
            <option>Encrypt</option>
            <option>Decrypt</option>
          </select>
          <button type="submit" name="submit" value="submit" class="btn btn-primary">Go!</button>
      </div>

    </div>
    <div class="row mt-3">
      <div class="col-md-6">
        <h2 class="mb-3">Result</h2>

        </form>
        <div class="form-group">
          <label for="result">Ciphertext/Plaintext</label>
          <input type="text" class="form-control" value="<?php echo $hasil ?>" readonly>
          <small id=" resulthelp" class="form-text text-muted">Result here!</small>
        </div>
      </div>
    </div>
  </div>

</body>

</html>