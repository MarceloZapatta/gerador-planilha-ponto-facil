<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gerador de planilha Ponto Fácil</title>
</head>

<body>
  <form action="gerar-planilha.php" method="post">
    <div>
      <label for="mes">Mês:</label>
      <select name="mes" id="mes">
        <option value="01" <?php echo date('m') === '01' ? 'selected' : '' ?>>Janeiro</option>
        <option value="02" <?php echo date('m') === '02' ? 'selected' : '' ?>>Fevereiro</option>
        <option value="03" <?php echo date('m') === '03' ? 'selected' : '' ?>>Março</option>
        <option value="04" <?php echo date('m') === '04' ? 'selected' : '' ?>>Abril</option>
        <option value="05" <?php echo date('m') === '05' ? 'selected' : '' ?>>Maio</option>
        <option value="06" <?php echo date('m') === '06' ? 'selected' : '' ?>>Junho</option>
        <option value="07" <?php echo date('m') === '07' ? 'selected' : '' ?>>Julho</option>
        <option value="08" <?php echo date('m') === '08' ? 'selected' : '' ?>>Agosto</option>
        <option value="09" <?php echo date('m') === '09' ? 'selected' : '' ?>>Setembro</option>
        <option value="10" <?php echo date('m') === '10' ? 'selected' : '' ?>>Outubro</option>
        <option value="11" <?php echo date('m') === '11' ? 'selected' : '' ?>>Novembro</option>
        <option value="12" <?php echo date('m') === '12' ? 'selected' : '' ?>>Dezembro</option>
      </select>
    </div>
    <button submit>Gerar</button>
  </form>
</body>

</html>