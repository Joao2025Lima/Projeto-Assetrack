<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AsseTrack - Movimentar ativo</title>
    <link rel="stylesheet" href="{{ asset('css/ativos.css') }}">
    <style>
        .pagina-mov { display: flex; min-height: 100vh; background: #f4f7fb; }
        .pagina-mov .menu-lateral { position: sticky; top: 0; height: 100vh; flex-shrink: 0; }
        .pagina-mov .conteudo-mov { flex: 1; padding: 30px; min-width: 0; font-family: Arial, sans-serif; }
        .conteudo-mov .sub { color: #7a8aa0; font-size: 12px; letter-spacing: .08em; text-transform: uppercase; }
        .conteudo-mov h1 { margin: 0 0 24px; font-size: 28px; color: #0f1b2d; }
        .caixa-mov { max-width: 560px; background: #fff; padding: 24px; border-radius: 14px; box-shadow: 0 2px 10px rgba(0,0,0,.05); }
        .caixa-mov label { display: block; margin-top: 14px; font-weight: bold; font-size: 14px; color: #0f1b2d; }
        .caixa-mov input, .caixa-mov select, .caixa-mov textarea { width: 100%; padding: 10px; margin-top: 6px; border: 1px solid #ccd5e3; border-radius: 8px; box-sizing: border-box; font-size: 15px; }
        .caixa-mov input[readonly] { background: #f4f7fb; }
        .caixa-mov button { margin-top: 20px; padding: 12px 18px; background: #0b5ed7; color: #fff; border: 0; border-radius: 8px; font-size: 15px; cursor: pointer; }
        .erro-mov { background: #fde8ea; color: #a1212e; padding: 10px; border-radius: 8px; margin-bottom: 10px; }
        .voltar-mov { display: inline-block; margin-top: 16px; color: #0b5ed7; text-decoration: none; }
    </style>
</head>
<body>
<div class="pagina-mov">

    @include('partials.sidebar')

    <main class="conteudo-mov">
        <div class="sub">Gestão de equipamentos</div>
        <h1>Movimentar ativo</h1>

        <div class="caixa-mov">

            @if ($errors->any())
                <div class="erro-mov">
                    @foreach ($errors->all() as $erro)
                        <div>{{ $erro }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('movimentar.store') }}">
                @csrf
                <input type="hidden" name="ativo_id" value="{{ $ativo->id }}">

                <label>Ativo</label>
                <input value="{{ $ativo->nome }} ({{ $ativo->patrimonio }})" readonly>

                <label>Local atual</label>
                <input value="{{ $ativo->local?->nome ?? 'Não registrado' }}" readonly>

                <label>Novo local</label>
                <select name="local_novo_id" required>
                    <option value="">Selecione...</option>
                    @foreach ($locais as $local)
                        <option value="{{ $local->id }}">{{ $local->nome }}</option>
                    @endforeach
                </select>

                <label>Observação</label>
                <textarea name="descricao" rows="3"></textarea>

                <button type="submit">Confirmar movimentação</button>
            </form>

            <a class="voltar-mov" href="{{ route('ativos.show', $ativo->id) }}">← Voltar aos detalhes do ativo</a>
        </div>
    </main>

</div>
</body>
</html>