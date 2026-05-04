<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Queima '26</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@800&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .nav-tabs { border-bottom: 2px solid rgba(255,255,255,0.1); }
        .nav-link { color: #888; font-weight: bold; text-transform: uppercase; border: none !important; }
        .nav-link.active { background: transparent !important; color: var(--neon-blue) !important; border-bottom: 3px solid var(--neon-blue) !important; }
        .table { color: #ccc; }
        .table-hover tbody tr:hover { background: rgba(255,255,255,0.05); color: #fff; }
        .stat-card { background: var(--glass-bg); border: 1px solid rgba(255,255,255,0.1); border-radius: 15px; padding: 1.5rem; text-align: center; transition: all 0.3s; }
        .stat-card:hover { transform: translateY(-5px); border-color: var(--neon-green); }
        .stat-number { font-family: 'Syne', sans-serif; font-size: 2.5rem; color: var(--neon-blue); margin: 0; }
        .stat-label { color: #888; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; }
        select option { background-color: #212529 !important; color: white !important; }
    </style>
</head>
<body class="login-container">

<nav class="navbar navbar-expand-lg navbar-dark sticky-top py-3">
    <div class="container">
        <a class="navbar-brand fs-2 fw-bold" href="index.php">QUEIMA<span style="color:var(--neon-blue)">'26</span></a>
        <div class="d-flex align-items-center">
            <span class="text-white small fw-bold me-3 text-uppercase">Painel de Controlo</span>
            <a href="index.php" class="btn btn-outline-light btn-sm me-2">Voltar ao Site</a>
            <a href="logout.php" class="btn btn-bilhetes">Sair</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <?php if(!empty($erro)): ?>
        <div class="alert alert-danger bg-danger text-white border-0 shadow-lg mb-4">⚠️ <?php echo htmlspecialchars($erro); ?></div>
    <?php endif; ?>
    <?php if(!empty($sucesso)): ?>
        <div class="alert alert-success bg-success text-white border-0 shadow-lg mb-4">✅ <?php echo htmlspecialchars($sucesso); ?></div>
    <?php endif; ?>

    <ul class="nav nav-tabs mb-5" id="adminTabs" role="tablist">
        <li class="nav-item"><button class="nav-link <?php echo $aba_ativa=='dashboard'?'active':''; ?>" data-bs-toggle="tab" data-bs-target="#dashboard" type="button">Dashboard</button></li>
        <li class="nav-item"><button class="nav-link <?php echo $aba_ativa=='artistas'?'active':''; ?>" data-bs-toggle="tab" data-bs-target="#artistas" type="button">Artistas</button></li>
        <li class="nav-item"><button class="nav-link <?php echo $aba_ativa=='eventos'?'active':''; ?>" data-bs-toggle="tab" data-bs-target="#eventos" type="button">Eventos</button></li>
        <li class="nav-item"><button class="nav-link <?php echo $aba_ativa=='barracas'?'active':''; ?>" data-bs-toggle="tab" data-bs-target="#barracas" type="button">Barracas</button></li>
        <li class="nav-item"><button class="nav-link <?php echo $aba_ativa=='faculdades'?'active':''; ?>" data-bs-toggle="tab" data-bs-target="#faculdades" type="button">Faculdades</button></li>
    </ul>

    <div class="tab-content" id="adminTabsContent">

        <!-- DASHBOARD -->
        <div class="tab-pane fade <?php echo $aba_ativa=='dashboard'?'show active':''; ?>" id="dashboard">
            <h4 class="text-white fw-bold mb-4" style="font-family:'Syne',sans-serif;">RESUMO DO SISTEMA</h4>
            <div class="row g-4 mb-5">
                <div class="col-md-4 col-lg-2"><div class="stat-card"><div class="stat-number"><?php echo $estatisticas['total_users']; ?></div><div class="stat-label">Utilizadores</div></div></div>
                <div class="col-md-4 col-lg-2"><div class="stat-card"><div class="stat-number"><?php echo $estatisticas['total_events']; ?></div><div class="stat-label">Eventos</div></div></div>
                <div class="col-md-4 col-lg-2"><div class="stat-card"><div class="stat-number"><?php echo $estatisticas['total_artists']; ?></div><div class="stat-label">Artistas</div></div></div>
                <div class="col-md-4 col-lg-2"><div class="stat-card"><div class="stat-number"><?php echo $estatisticas['total_tents']; ?></div><div class="stat-label">Barracas</div></div></div>
                <div class="col-md-4 col-lg-2"><div class="stat-card"><div class="stat-number"><?php echo $estatisticas['total_agenda']; ?></div><div class="stat-label">Agendamentos</div></div></div>
                <div class="col-md-4 col-lg-2"><div class="stat-card"><div class="stat-number" style="color:var(--neon-red)"><?php echo $estatisticas['events_next_48h']; ?></div><div class="stat-label">Próx. 48h</div></div></div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card event-card p-4">
                        <h6 class="text-white-50 fw-bold text-uppercase mb-3">🏆 Evento Melhor Classificado</h6>
                        <?php if($estatisticas['top_event']): ?>
                            <h4 class="text-white fw-bold"><?php echo htmlspecialchars($estatisticas['top_event']['name']); ?></h4>
                            <p class="text-white-50 mb-0">⭐ <?php echo number_format($estatisticas['top_event']['media'],1); ?> (<?php echo $estatisticas['top_event']['votos']; ?> votos)</p>
                        <?php else: ?><p class="text-muted">Sem dados suficientes.</p><?php endif; ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card event-card p-4">
                        <h6 class="text-white-50 fw-bold text-uppercase mb-3">🏆 Barraca Melhor Classificada</h6>
                        <?php if($estatisticas['top_tent']): ?>
                            <h4 class="text-white fw-bold"><?php echo htmlspecialchars($estatisticas['top_tent']['name']); ?></h4>
                            <p class="text-white-50 mb-0">⭐ <?php echo number_format($estatisticas['top_tent']['media'],1); ?> (<?php echo $estatisticas['top_tent']['votos']; ?> votos)</p>
                        <?php else: ?><p class="text-muted">Sem dados suficientes.</p><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- ARTISTAS -->
        <div class="tab-pane fade <?php echo $aba_ativa=='artistas'?'show active':''; ?>" id="artistas">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card event-card shadow-lg">
                        <div class="card-header bg-transparent border-0 pt-4 px-4 text-primary">ADICIONAR ARTISTA</div>
                        <div class="card-body p-4">
                            <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                                <input type="hidden" name="add_artist" value="1">
                                <input type="hidden" name="tab" value="artistas">
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
                        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
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
                            <table class="table table-sm table-dark table-hover table-borderless align-middle">
                                <thead class="text-secondary small"><tr><th>IMAGEM</th><th>NOME</th><th>GÉNERO</th><th>PAÍS</th><th class="text-end">AÇÕES</th></tr></thead>
                                <tbody>
                                    <?php foreach($artistas as $art): ?>
                                    <tr>
                                        <td><img src="uploads/<?php echo htmlspecialchars($art['image'] ?? 'default.jpg'); ?>" class="rounded" style="width:40px;height:40px;object-fit:cover;"></td>
                                        <td class="fw-bold text-white"><?php echo htmlspecialchars($art['name']); ?></td>
                                        <td class="text-white"><?php echo htmlspecialchars($art['musical_genre']); ?></td>
                                        <td class="text-white"><?php echo htmlspecialchars($art['country']); ?></td>
                                        <td class="text-end"><button type="button" class="btn btn-outline-info btn-sm me-1" data-bs-toggle="modal" data-bs-target="#editArt<?php echo $art['id']; ?>">Editar</button><a href="details.php?type=artist&id=<?php echo $art['id']; ?>" class="btn btn-outline-secondary btn-sm me-1" target="_blank">Ver</a><a href="admin.php?delete_artist=<?php echo $art['id']; ?>&tab=artistas" class="btn btn-outline-danger btn-sm" onclick="return confirm('Deseja apagar este artista?');">Apagar</a></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- EVENTOS -->
        <div class="tab-pane fade <?php echo $aba_ativa=='eventos'?'show active':''; ?>" id="eventos">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card event-card border-0">
                        <div class="card-header bg-transparent pt-4 px-4 text-success">CRIAR EVENTO</div>
                        <div class="card-body p-4">
                            <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                                <input type="hidden" name="add_event" value="1">
                                <input type="hidden" name="tab" value="eventos">
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
                                <div class="mb-3">
                                    <select name="localizacao" class="form-select bg-dark text-white border-secondary" required>
                                        <option value="">Recinto/Local...</option>
                                        <option value="Queimódromo - Palco Principal">Queimódromo - Palco Principal</option>
                                        <option value="Queimódromo - Tenda Eletrónica">Queimódromo - Tenda Eletrónica</option>
                                        <option value="Queimódromo - Recinto das Barracas">Queimódromo - Recinto das Barracas</option>
                                        <option value="Cordoaria">Cordoaria</option>
                                        <option value="Baixa do Porto / Aliados">Baixa do Porto / Aliados</option>
                                        <option value="Outro">Outro</option>
                                    </select>
                                </div>
                                <div class="mb-3"><textarea name="descricao" class="form-control" rows="2" placeholder="Descrição do evento"></textarea></div>
                                <div class="mb-3"><input type="file" name="imagem_evento" class="form-control" accept="image/*"></div>
                                <div class="mb-3">
                                    <select name="tent_id" class="form-select bg-dark text-white border-secondary">
                                        <option value="">Associar à Barraca (Opcional)</option>
                                        <?php foreach($tendas as $tenda): ?>
                                            <option value="<?php echo $tenda['id']; ?>"><?php echo htmlspecialchars($tenda['name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label small text-muted">ARTISTAS (para Concertos)</label>
                                    <div class="bg-dark p-2 rounded border border-secondary" style="max-height:120px;overflow-y:auto;">
                                        <?php foreach($artistas as $art): ?>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="artistas[]" value="<?php echo $art['id']; ?>" id="art_<?php echo $art['id']; ?>">
                                                <label class="form-check-label small text-white" for="art_<?php echo $art['id']; ?>"><?php echo htmlspecialchars($art['name']); ?></label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 fw-bold" style="background: var(--neon-green); color: black; border: none;">CRIAR EVENTO</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card event-card p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                            <h5 class="text-white fw-bold mb-0">GESTÃO DE EVENTOS</h5>
                            <a href="admin.php?export_events=1" class="btn btn-sm btn-outline-success fw-bold">EXPORTAR CSV</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-dark table-hover table-borderless align-middle">
                                <thead class="text-secondary small"><tr><th>IMAGEM</th><th>EVENTO</th><th>DATA/HORA</th><th>TIPO</th><th class="text-end">AÇÕES</th></tr></thead>
                                <tbody>
                                    <?php foreach($eventos as $evento): ?>
                                    <tr>
                                        <td><img src="uploads/<?php echo htmlspecialchars($evento['image'] ?? 'default.jpg'); ?>" class="rounded" style="width:40px;height:40px;object-fit:cover;"></td>
                                        <td><strong class="text-white"><?php echo htmlspecialchars($evento['name']); ?></strong><br><small class="text-muted"><?php echo htmlspecialchars($evento['location']); ?></small></td>
                                        <td class="text-white"><?php echo date('d/m H:i', strtotime($evento['date_time'])); ?></td>
                                        <td><span class="badge bg-dark border border-secondary"><?php echo htmlspecialchars($evento['type']); ?></span></td>
                                        <td class="text-end"><button type="button" class="btn btn-outline-info btn-sm me-1" data-bs-toggle="modal" data-bs-target="#editEvt<?php echo $evento['id']; ?>">Editar</button><a href="details.php?type=event&id=<?php echo $evento['id']; ?>" class="btn btn-outline-secondary btn-sm me-1" target="_blank">Ver</a><a href="admin.php?delete_event=<?php echo $evento['id']; ?>&tab=eventos" class="btn btn-outline-danger btn-sm" onclick="return confirm('Eliminar este evento?');">Apagar</a></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BARRACAS -->
        <div class="tab-pane fade <?php echo $aba_ativa=='barracas'?'show active':''; ?>" id="barracas">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card event-card shadow-lg">
                        <div class="card-header bg-transparent pt-4 px-4 text-warning">NOVA BARRACA</div>
                        <div class="card-body p-4">
                            <form method="POST" class="needs-validation" novalidate>
                                <input type="hidden" name="add_tent" value="1">
                                <input type="hidden" name="tab" value="barracas">
                                <div class="mb-3"><input type="text" name="nome" class="form-control" required placeholder="Nome da Barraca"></div>
                                <div class="mb-3">
                                    <select name="faculty_id" class="form-select bg-dark text-white border-secondary" required>
                                        <option value="">Faculdade Representante...</option>
                                        <?php foreach($faculdades as $fac): ?>
                                            <option value="<?php echo $fac['id']; ?>"><?php echo htmlspecialchars($fac['name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <select name="localizacao" class="form-select bg-dark text-white border-secondary" required>
                                        <option value="">Selecionar localização...</option>
                                        <option value="Praça Central">Praça Central</option>
                                        <option value="Rua A">Rua A</option>
                                        <option value="Rua B">Rua B</option>
                                        <option value="Rua C">Rua C</option>
                                        <option value="Zona de Restauração">Zona de Restauração</option>
                                        <option value="Outro">Outro</option>
                                    </select>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-6"><label class="small text-muted">ABERTURA</label><input type="time" name="abertura" class="form-control" required></div>
                                    <div class="col-6"><label class="small text-muted">FECHO</label><input type="time" name="fecho" class="form-control" required></div>
                                </div>
                                <div class="mb-4"><textarea name="descricao" class="form-control" rows="2" placeholder="Breve descrição da barraca"></textarea></div>
                                <button type="submit" class="btn btn-warning w-100 fw-bold">INSTALAR BARRACA</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card event-card p-4">
                        <h5 class="text-white fw-bold mb-4">MAPA DE BARRACAS</h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-dark table-hover table-borderless align-middle">
                                <thead class="text-secondary small"><tr><th>BARRACA</th><th>FACULDADE</th><th>LOCAL</th><th>HORÁRIO</th><th class="text-end">AÇÕES</th></tr></thead>
                                <tbody>
                                    <?php foreach($tendas_detalhadas as $tenda): ?>
                                    <tr>
                                        <td class="text-white fw-bold"><?php echo htmlspecialchars($tenda['name']); ?></td>
                                        <td><span class="badge bg-secondary"><?php echo htmlspecialchars($tenda['fac_acronym']); ?></span></td>
                                        <td class="small text-white"><?php echo htmlspecialchars($tenda['location']); ?></td>
                                        <td class="small text-white"><?php echo date('H:i', strtotime($tenda['opening_hours'])) . ' - ' . date('H:i', strtotime($tenda['closing_hours'])); ?></td>
                                        <td class="text-end"><button type="button" class="btn btn-outline-info btn-sm me-1" data-bs-toggle="modal" data-bs-target="#editTent<?php echo $tenda['id']; ?>">Editar</button><a href="details.php?type=tent&id=<?php echo $tenda['id']; ?>" class="btn btn-outline-secondary btn-sm me-1" target="_blank">Ver</a><a href="admin.php?delete_tent=<?php echo $tenda['id']; ?>&tab=barracas" class="btn btn-outline-danger btn-sm" onclick="return confirm('Remover esta barraca?');">Apagar</a></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FACULDADES -->
        <div class="tab-pane fade <?php echo $aba_ativa=='faculdades'?'show active':''; ?>" id="faculdades">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card event-card shadow-lg">
                        <div class="card-header bg-transparent pt-4 px-4" style="color: var(--neon-blue)">NOVA FACULDADE</div>
                        <div class="card-body p-4">
                            <form method="POST" class="needs-validation" novalidate>
                                <input type="hidden" name="add_faculty" value="1">
                                <input type="hidden" name="tab" value="faculdades">
                                <div class="mb-3"><input type="text" name="nome" class="form-control" required placeholder="Nome completo"></div>
                                <div class="mb-3"><input type="text" name="acronimo" class="form-control" required placeholder="Acrónimo (ex: FEUP)" maxlength="20"></div>
                                <div class="mb-3"><input type="text" name="cor" class="form-control" placeholder="Cor representativa (ex: Azul)"></div>
                                <div class="mb-4"><textarea name="descricao" class="form-control" rows="3" placeholder="Descrição da faculdade"></textarea></div>
                                <button type="submit" class="btn btn-primary w-100 fw-bold" style="background: var(--neon-blue); border: none;">ADICIONAR FACULDADE</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card event-card p-4">
                        <h5 class="text-white fw-bold mb-4">FACULDADES REGISTADAS</h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-dark table-hover table-borderless align-middle">
                                <thead class="text-secondary small"><tr><th>NOME</th><th>ACRÓNIMO</th><th>COR</th><th class="text-end">AÇÕES</th></tr></thead>
                                <tbody>
                                    <?php foreach($faculdades as $fac): ?>
                                    <tr>
                                        <td class="text-white fw-bold"><?php echo htmlspecialchars($fac['name']); ?></td>
                                        <td><span class="badge bg-dark border border-secondary"><?php echo htmlspecialchars($fac['acronym']); ?></span></td>
                                        <td class="small text-white"><?php echo htmlspecialchars($fac['colour'] ?? '-'); ?></td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-outline-info btn-sm me-1" data-bs-toggle="modal" data-bs-target="#editFac<?php echo $fac['id']; ?>">Editar</button>
                                            <a href="admin.php?delete_faculty=<?php echo $fac['id']; ?>&tab=faculdades" class="btn btn-outline-danger btn-sm" onclick="return confirm('Remover <?php echo htmlspecialchars($fac['name']); ?>?

Atenção: Barracas associadas podem ser afetadas.');">Apagar</a>
                                        </td>
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

<!-- MODAIS DE EDIÇÃO -->
<?php foreach($artistas as $art): ?>
<!-- Modal Editar Artista -->
<div class="modal fade" id="editArt<?php echo $art['id']; ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border-secondary">
            <div class="modal-header border-secondary"><h5 class="modal-title text-white">Editar Artista</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="edit_artist" value="1">
                    <input type="hidden" name="artist_id" value="<?php echo $art['id']; ?>">
                    <input type="hidden" name="tab" value="artistas">
                    <div class="mb-3"><label class="form-label small text-muted">Nome</label><input type="text" name="nome" class="form-control" value="<?php echo htmlspecialchars($art['name']); ?>" required></div>
                    <div class="mb-3"><label class="form-label small text-muted">Género Musical</label><input type="text" name="genero" class="form-control" value="<?php echo htmlspecialchars($art['musical_genre']); ?>" required></div>
                    <div class="mb-3"><label class="form-label small text-muted">País</label><input type="text" name="pais" class="form-control" value="<?php echo htmlspecialchars($art['country']); ?>" required></div>
                    <div class="mb-3"><label class="form-label small text-muted">Imagem</label><input type="file" name="imagem_artista" class="form-control" accept="image/*"><small class="text-muted d-block mt-1">Deixe em branco para manter a imagem atual</small></div>
                    <div class="mb-3"><label class="form-label small text-muted">Biografia Curta</label><textarea name="biografia" class="form-control" rows="3" required><?php echo htmlspecialchars($art['short_biography']); ?></textarea></div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold">GUARDAR ALTERAÇÕES</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<?php foreach($eventos as $evento): ?>
<!-- Modal Editar Evento -->
<div class="modal fade" id="editEvt<?php echo $evento['id']; ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border-secondary">
            <div class="modal-header border-secondary"><h5 class="modal-title text-white">Editar Evento</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="edit_event" value="1">
                    <input type="hidden" name="event_id" value="<?php echo $evento['id']; ?>">
                    <input type="hidden" name="tab" value="eventos">
                    <div class="mb-3"><label class="form-label small text-muted">Nome do Evento</label><input type="text" name="nome" class="form-control" value="<?php echo htmlspecialchars($evento['name']); ?>" required></div>
                    <div class="mb-3"><label class="form-label small text-muted">Tipo de Evento</label>
                        <select name="tipo" class="form-select bg-dark text-white border-secondary" required>
                            <option value="">Tipo de Evento...</option>
                            <option value="Academic ceremony" <?php echo $evento['type'] === 'Academic ceremony' ? 'selected' : ''; ?>>Cerimónia Académica</option>
                            <option value="Concert" <?php echo $evento['type'] === 'Concert' ? 'selected' : ''; ?>>Concerto</option>
                            <option value="Cultural activity" <?php echo $evento['type'] === 'Cultural activity' ? 'selected' : ''; ?>>Atividade Cultural</option>
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label small text-muted">Data/Hora</label><input type="datetime-local" name="data_hora" class="form-control" value="<?php echo date('Y-m-d\TH:i', strtotime($evento['date_time'])); ?>" required></div>
                    <div class="mb-3"><label class="form-label small text-muted">Recinto/Local</label>
                        <select name="localizacao" class="form-select bg-dark text-white border-secondary" required>
                            <option value="">Recinto/Local...</option>
                            <option value="Queimódromo - Palco Principal" <?php echo $evento['location'] === 'Queimódromo - Palco Principal' ? 'selected' : ''; ?>>Queimódromo - Palco Principal</option>
                            <option value="Queimódromo - Tenda Eletrónica" <?php echo $evento['location'] === 'Queimódromo - Tenda Eletrónica' ? 'selected' : ''; ?>>Queimódromo - Tenda Eletrónica</option>
                            <option value="Queimódromo - Recinto das Barracas" <?php echo $evento['location'] === 'Queimódromo - Recinto das Barracas' ? 'selected' : ''; ?>>Queimódromo - Recinto das Barracas</option>
                            <option value="Cordoaria" <?php echo $evento['location'] === 'Cordoaria' ? 'selected' : ''; ?>>Cordoaria</option>
                            <option value="Baixa do Porto / Aliados" <?php echo $evento['location'] === 'Baixa do Porto / Aliados' ? 'selected' : ''; ?>>Baixa do Porto / Aliados</option>
                            <option value="Outro" <?php echo $evento['location'] === 'Outro' ? 'selected' : ''; ?>>Outro</option>
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label small text-muted">Descrição</label><textarea name="descricao" class="form-control" rows="2"><?php echo htmlspecialchars($evento['description'] ?? ''); ?></textarea></div>
                    <div class="mb-3"><label class="form-label small text-muted">Imagem</label><input type="file" name="imagem_evento" class="form-control" accept="image/*"><small class="text-muted d-block mt-1">Deixe em branco para manter a imagem atual</small></div>
                    <div class="mb-3"><label class="form-label small text-muted">Associar à Barraca</label>
                        <select name="tent_id" class="form-select bg-dark text-white border-secondary">
                            <option value="">Nenhuma barraca</option>
                            <?php foreach($tendas as $tenda): ?>
                                <option value="<?php echo $tenda['id']; ?>" <?php echo $evento['tent_id'] == $tenda['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($tenda['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label small text-muted">ARTISTAS (para Concertos)</label>
                        <div class="bg-dark p-2 rounded border border-secondary" style="max-height:120px;overflow-y:auto;">
                            <?php 
                            $artistas_evento = getEventArtists($pdo, $evento['id']);
                            $ids_artistas_evento = array_column($artistas_evento, 'id');
                            foreach($artistas as $art): 
                            ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="artistas[]" value="<?php echo $art['id']; ?>" id="art_evt_<?php echo $evento['id']; ?>_<?php echo $art['id']; ?>" <?php echo in_array($art['id'], $ids_artistas_evento) ? 'checked' : ''; ?>>
                                    <label class="form-check-label small text-white" for="art_evt_<?php echo $evento['id']; ?>_<?php echo $art['id']; ?>"><?php echo htmlspecialchars($art['name']); ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold" style="background: var(--neon-green); color: black; border: none;">GUARDAR ALTERAÇÕES</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<?php foreach($tendas_detalhadas as $tenda): ?>
<!-- Modal Editar Barraca -->
<div class="modal fade" id="editTent<?php echo $tenda['id']; ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border-secondary">
            <div class="modal-header border-secondary"><h5 class="modal-title text-white">Editar Barraca</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <form method="POST">
                    <input type="hidden" name="edit_tent" value="1">
                    <input type="hidden" name="tent_id" value="<?php echo $tenda['id']; ?>">
                    <input type="hidden" name="tab" value="barracas">
                    <div class="mb-3"><label class="form-label small text-muted">Nome da Barraca</label><input type="text" name="nome" class="form-control" value="<?php echo htmlspecialchars($tenda['name']); ?>" required></div>
                    <div class="mb-3"><label class="form-label small text-muted">Faculdade Representante</label>
                        <select name="faculty_id" class="form-select bg-dark text-white border-secondary" required>
                            <option value="">Faculdade...</option>
                            <?php foreach($faculdades as $fac): ?>
                                <option value="<?php echo $fac['id']; ?>" <?php echo $tenda['faculty_id'] == $fac['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($fac['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label small text-muted">Localização no Recinto</label>
                        <select name="localizacao" class="form-select bg-dark text-white border-secondary" required>
                            <option value="">Selecionar localização...</option>
                            <option value="Praça Central" <?php echo $tenda['location'] === 'Praça Central' ? 'selected' : ''; ?>>Praça Central</option>
                            <option value="Rua A" <?php echo $tenda['location'] === 'Rua A' ? 'selected' : ''; ?>>Rua A</option>
                            <option value="Rua B" <?php echo $tenda['location'] === 'Rua B' ? 'selected' : ''; ?>>Rua B</option>
                            <option value="Rua C" <?php echo $tenda['location'] === 'Rua C' ? 'selected' : ''; ?>>Rua C</option>
                            <option value="Zona de Restauração" <?php echo $tenda['location'] === 'Zona de Restauração' ? 'selected' : ''; ?>>Zona de Restauração</option>
                            <option value="Outro" <?php echo $tenda['location'] === 'Outro' ? 'selected' : ''; ?>>Outro</option>
                        </select>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6"><label class="form-label small text-muted">ABERTURA</label><input type="time" name="abertura" class="form-control" value="<?php echo date('H:i', strtotime($tenda['opening_hours'])); ?>" required></div>
                        <div class="col-6"><label class="form-label small text-muted">FECHO</label><input type="time" name="fecho" class="form-control" value="<?php echo date('H:i', strtotime($tenda['closing_hours'])); ?>" required></div>
                    </div>
                    <div class="mb-3"><label class="form-label small text-muted">Descrição</label><textarea name="descricao" class="form-control" rows="2"><?php echo htmlspecialchars($tenda['description'] ?? ''); ?></textarea></div>
                    <button type="submit" class="btn btn-warning w-100 fw-bold">GUARDAR ALTERAÇÕES</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<?php foreach($faculdades as $fac): ?>
<!-- Modal Editar Faculdade -->
<div class="modal fade" id="editFac<?php echo $fac['id']; ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border-secondary">
            <div class="modal-header border-secondary"><h5 class="modal-title text-white">Editar Faculdade</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <form method="POST">
                    <input type="hidden" name="edit_faculty" value="1">
                    <input type="hidden" name="faculty_id" value="<?php echo $fac['id']; ?>">
                    <input type="hidden" name="tab" value="faculdades">
                    <div class="mb-3"><input type="text" name="nome" class="form-control" value="<?php echo htmlspecialchars($fac['name']); ?>" required></div>
                    <div class="mb-3"><input type="text" name="acronimo" class="form-control" value="<?php echo htmlspecialchars($fac['acronym']); ?>" required></div>
                    <div class="mb-3"><input type="text" name="cor" class="form-control" value="<?php echo htmlspecialchars($fac['colour'] ?? ''); ?>"></div>
                    <div class="mb-3"><textarea name="descricao" class="form-control" rows="2"><?php echo htmlspecialchars($fac['description'] ?? ''); ?></textarea></div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold">GUARDAR ALTERAÇÕES</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<script>
// Validação Bootstrap
(function () {
  'use strict'
  var forms = document.querySelectorAll('.needs-validation')
  Array.prototype.slice.call(forms).forEach(function (form) {
    form.addEventListener('submit', function (event) {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }
      form.classList.add('was-validated')
    }, false)
  })
})()
</script>
</body>
</html>