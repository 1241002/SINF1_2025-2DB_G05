<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?> - Queima das Fitas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="card shadow-sm border-0 mx-auto" style="max-width: 700px;">
        <div class="card-body p-5">
            <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm mb-4">← Voltar</a>
            
            <?php if($type === 'artist'): ?>
                <h1 class="text-primary fw-bold"><?php echo htmlspecialchars($data['name']); ?></h1>
                <p class="badge bg-info text-dark"><?php echo htmlspecialchars($data['musical_genre']); ?></p>
                <p class="text-muted"><strong>Origem:</strong> <?php echo htmlspecialchars($data['country']); ?></p>
                <hr>
                <h5>Biografia</h5>
                <p><?php echo nl2br(htmlspecialchars($data['short_biography'])); ?></p>

            <?php elseif($type === 'tent'): ?>
                <h1 class="text-success fw-bold"><?php echo htmlspecialchars($data['name']); ?></h1>
                <p class="badge bg-dark"><?php echo htmlspecialchars($data['fac_acronym']); ?> - <?php echo htmlspecialchars($data['fac_name']); ?></p>
                <hr>
                <p><strong>📍 Localização:</strong> <?php echo htmlspecialchars($data['location']); ?></p>
                <p><strong>⏰ Horário:</strong> <?php echo date('H:i', strtotime($data['opening_hours'])); ?> - <?php echo date('H:i', strtotime($data['closing_hours'])); ?></p>
                <h5 class="mt-4">Sobre a Barraca</h5>
                <p><?php echo nl2br(htmlspecialchars($data['description'])); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>