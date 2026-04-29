<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Queima '26</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Fontes e CSS Principal -->
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@800&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Ajustes específicos para o Dashboard Admin */
        .nav-tabs { border-bottom: 2px solid rgba(255,255,255,0.1); }
        .nav-link { color: #888; font-weight: bold; text-transform: uppercase; border: none !important; }
        .nav-link.active { 
            background: transparent !important; 
            color: var(--neon-blue) !important; 
            border-bottom: 3px solid var(--neon-blue) !important; 
        }
        .table { color: #ccc; }
        .table-hover tbody tr:hover { background: rgba(255,255,255,0.05); color: #fff; }
        .card-header { font-family: 'Syne', sans-serif; letter-spacing: 1px; font-weight: bold; }
    </style>
</head>
<body class="login-container">

<nav class="navbar navbar-expand-lg navbar-dark sticky-top py-3">
    <div class="container">
        <a class="navbar-brand fs-2 fw-bold" href="index.php">QUEIMA<span style="color:var(--neon-blue)">'26</span></a>
        <div class="d-flex align-items-center">
            <span class="text-muted small fw-bold me-3 text-uppercase">Painel de Controlo</span>
            <a href="index.php" class="btn btn-outline-light btn-sm me-2">Voltar ao Site</a>
            <a href="logout.php" class="btn btn-bilhetes">Sair</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <!-- Alertas Estilizados -->
    <?php if(!empty($erro)): ?>
        <div class="alert alert-danger bg-danger text-white border-0 shadow-lg mb-4">⚠️ <?php echo htmlspecialchars($erro); ?></div>
    <?php endif; ?>
    <?php if(!empty($sucesso)): ?>
        <div class="alert alert-success bg-success text-white border-0 shadow-lg mb-4">✅ <?php echo htmlspecialchars($sucesso); ?></div>
    <?php endif; ?>

    <!-- Abas de Navegação -->
    <ul class="nav nav-tabs mb-5" id="adminTabs" role="tablist">
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#artistas" type="button">Artistas</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#eventos" type="button">Eventos</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#barracas" type="button">Barracas</button></li>
    </ul>

    <div class="tab-content" id="adminTabsContent">
        
        <!-- ABA ARTISTAS -->
        <div class="tab-pane fade show active" id="artistas">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card event-card shadow-lg">
                        <div class="card-header bg-transparent border-0 pt-4 px-4 text-primary">ADICIONAR ARTISTA</div>
                        <div class="card-body p-4">
                            <form method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="add_artist" value="1">
                                <div class="mb-3"><input type="text" name="nome" class="form-control" required placeholder="Nome do Artista"></div>
                                <div class="mb-3"><input type="text" name="genero" class="form-control" required placeholder="Género Musical"></div>
                                <div class="mb-3"><input type="text" name="pais" class="form-control" required placeholder="País"></div>
                                <div class="mb-3"><input type="file" name="imagem_artista" class="form-control" accept="image/*"></div>
                                <div class="mb-4"><textarea name="biografia" class="form-control" rows="3" required placeholder="Biografia Curta"></textarea></div>
                                <button type="submit" class="btn btn-primary w-100 fw-bold">GUARDAR ARTISTA</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card event-card p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="text-white fw-bold mb-0">LISTA DE ARTISTAS</h5>
                            <div class="d-flex gap-2">
                                <form method="POST" enctype="multipart/form-data" class="d-flex align-items-center">
                                    <input type="hidden" name="import_artists" value="1">
                                    <input type="file" name="csv_file" class="form-control form-control-sm me-2 bg-dark text-white border-secondary" accept=".csv" required style="width: 150px;">
                                    <button type="submit" class="btn btn-sm btn-outline-info fw-bold">IMPORTAR</button>
                                </form>
                                <a href="admin.php?export_artists=1" class="btn btn-sm btn-outline-success fw-bold">EXPORTAR</a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead class="text-secondary small"><tr><th>NOME</th><th>GÉNERO</th><th>PAÍS</th><th class="text-end">AÇÕES</th></tr></thead>
                                <tbody>
                                    <?php foreach($artistas as $art): ?>
                                    <tr>
                                        <td class="fw-bold text-white"><?php echo htmlspecialchars($art['name']); ?></td>
                                        <td><?php echo htmlspecialchars($art['musical_genre']); ?></td>
                                        <td><?php echo htmlspecialchars($art['country']); ?></td>
                                        <td class="text-end"><a href="admin.php?delete_artist=<?php echo $art['id']; ?>" class="btn btn-outline-danger btn-sm px-3" onclick="return confirm('Deseja apagar este artista?');">Apagar</a></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ABA EVENTOS -->
        <div class="tab-pane fade" id="eventos">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card event-card border-0">
                        <div class="card-header bg-transparent pt-4 px-4 text-success">CRIAR EVENTO</div>
                        <div class="card-body p-4">
                            <form method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="add_event" value="1">
                                <div class="mb-3"><input type="text" name="nome" class="form-control" required placeholder="Nome do Evento"></div>
                                <div class="mb-3">
                                    <select name="tipo" class="form-select bg-dark text-white border-secondary" required>
                                        <option value="">Tipo de Evento...</option>
                                        <option value="Academic ceremony">Cerimónia Académica</option>
                                        <option value="Concert">Concerto</option>
                                        <option value="Cultural activity">Atividade Cultural</option>
                                    </select>
                                </div>
                                <div class="mb-3"><input type="datetime-local" name="data_hora" class="form-control" required></div>
                                <div class="mb-3"><input type="text" name="localizacao" class="form-control" required placeholder="Recinto/Local"></div>
                                <div class="mb-3"><input type="file" name="imagem_evento" class="form-control" accept="image/*"></div>
                                <div class="mb-4">
                                    <select name="tent_id" class="form-select bg-dark text-white border-secondary">
                                        <option value="">Associar à Barraca (Opcional)</option>
                                        <?php foreach($tendas as $tenda): ?>
                                            <option value="<?php echo $tenda['id']; ?>"><?php echo htmlspecialchars($tenda['name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 fw-bold" style="background: var(--neon-green); color: black; border: none;">CRIAR EVENTO</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card event-card p-4">
                        <h5 class="text-white fw-bold mb-4">GESTÃO DE EVENTOS</h5>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead class="text-secondary small"><tr><th>EVENTO</th><th>DATA/HORA</th><th>TIPO</th><th class="text-end">AÇÕES</th></tr></thead>
                                <tbody>
                                    <?php foreach($eventos as $evento): ?>
                                    <tr>
                                        <td><strong class="text-white"><?php echo htmlspecialchars($evento['name']); ?></strong><br><small class="text-muted"><?php echo htmlspecialchars($evento['location']); ?></small></td>
                                        <td><?php echo date('d/m H:i', strtotime($evento['date_time'])); ?></td>
                                        <td><span class="badge bg-dark border border-secondary"><?php echo htmlspecialchars($evento['type']); ?></span></td>
                                        <td class="text-end"><a href="admin.php?delete_event=<?php echo $evento['id']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Eliminar este evento?');">Apagar</a></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ABA BARRACAS -->
        <div class="tab-pane fade" id="barracas">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card event-card shadow-lg">
                        <div class="card-header bg-transparent pt-4 px-4 text-warning">NOVA BARRACA</div>
                        <div class="card-body p-4">
                            <form method="POST">
                                <input type="hidden" name="add_tent" value="1">
                                <div class="mb-3"><input type="text" name="nome" class="form-control" required placeholder="Nome da Barraca"></div>
                                <div class="mb-3">
                                    <select name="faculty_id" class="form-select bg-dark text-white border-secondary" required>
                                        <option value="">Faculdade Representante...</option>
                                        <?php foreach($faculdades as $fac): ?>
                                            <option value="<?php echo $fac['id']; ?>"><?php echo htmlspecialchars($fac['name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-6"><label class="small text-muted">ABERTURA</label><input type="time" name="abertura" class="form-control" required></div>
                                    <div class="col-6"><label class="small text-muted">FECHO</label><input type="time" name="fecho" class="form-control" required></div>
                                </div>
                                <div class="mb-4"><textarea name="description" class="form-control" rows="2" placeholder="Breve descrição da barraca"></textarea></div>
                                <button type="submit" class="btn btn-warning w-100 fw-bold">INSTALAR BARRACA</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card event-card p-4">
                        <h5 class="text-white fw-bold mb-4">MAPA DE BARRACAS</h5>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead class="text-secondary small"><tr><th>BARRACA</th><th>FACULDADE</th><th>HORÁRIO</th><th class="text-end">AÇÕES</th></tr></thead>
                                <tbody>
                                    <?php foreach($tendas_detalhadas as $tenda): ?>
                                    <tr>
                                        <td class="text-white fw-bold"><?php echo htmlspecialchars($tenda['name']); ?></td>
                                        <td><span class="badge bg-secondary"><?php echo htmlspecialchars($tenda['fac_acronym']); ?></span></td>
                                        <td class="small"><?php echo date('H:i', strtotime($tenda['opening_hours'])) . ' - ' . date('H:i', strtotime($tenda['closing_hours'])); ?></td>
                                        <td class="text-end"><a href="admin.php?delete_tent=<?php echo $tenda['id']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Remover esta barraca?');">Apagar</a></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<footer class="mt-5 py-5 text-center text-muted border-top border-secondary">
    <p class="small">&copy; 2026 ADMIN CONSOLE — QUEIMA DAS FITAS DO PORTO</p>
</footer>

</body>
</html>