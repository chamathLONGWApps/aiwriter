<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/script.js"></script>
    <script src="js/app.js"></script>
    <title>Article writer</title>
</head>

<body>
    <div class="card">
        <?php if (!empty($errors)) : ?>
            <?php foreach ($errors as $error) : ?>
                <li><?= esc($error) ?></li>
            <?php endforeach ?>
        <?php endif ?>
        <div class="card-header">Upload CSV</div>
        <div class="card-body">
            <?= form_open_multipart(base_url() . 'upload') ?>
            <div class="row">
                <div class="col-9 mb-3">
                    <label class="form-label">Upload csv</label>
                    <input type="file" name="file" class="form-control" placeholder="csv">
                </div>
                <div class="col-3 py-4">
                    <button type="submit" class="btn btn-primary align-middle">Upload</button>
                </div>
            </div>
            </form>
        </div>
    </div>

    <div id="alert-container">
    </div>
    <!-- loader -->
    <div id="page-loader">
        <div class="spinner-border text-primary m-auto" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

<script>
    // $(function() {
    //     app.initHome()
    // })
</script>

</html>