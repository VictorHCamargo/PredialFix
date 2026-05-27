<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Novo Chamado – PredialFix SENAI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { senai: { red: '#E3000F', dark: '#1a1a1a' } },
                    fontFamily: { sans: ['Segoe UI', 'system-ui', 'sans-serif'] },
                },
            },
        };
    </script>
</head>
<body class="flex min-h-screen flex-col bg-white font-sans">
    <?php if (isset($component)) { $__componentOriginala591787d01fe92c5706972626cdf7231 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala591787d01fe92c5706972626cdf7231 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.navbar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('navbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala591787d01fe92c5706972626cdf7231)): ?>
<?php $attributes = $__attributesOriginala591787d01fe92c5706972626cdf7231; ?>
<?php unset($__attributesOriginala591787d01fe92c5706972626cdf7231); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala591787d01fe92c5706972626cdf7231)): ?>
<?php $component = $__componentOriginala591787d01fe92c5706972626cdf7231; ?>
<?php unset($__componentOriginala591787d01fe92c5706972626cdf7231); ?>
<?php endif; ?>

    <!-- Conteúdo -->
    <main class="mx-auto w-full max-w-2xl flex-1 px-6 py-8">
        <h1 class="mb-6 text-lg font-semibold text-gray-800">
            Olá, <span class="font-bold"><?php echo e(Auth::user()->nome); ?></span>. Relate o problema abaixo.
        </h1>

        <?php if($errors->any()): ?>
            <div
                class="mb-5 rounded border border-red-300 bg-red-100 px-4 py-3 text-xs text-red-700"
            >
                <ul class="list-disc space-y-1 pl-4">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form
            method="POST"
            action="<?php echo e(route('chamados.store')); ?>"
            enctype="multipart/form-data"
            class="flex flex-col gap-5"
        >
            <?php echo csrf_field(); ?>

            <!-- Descrição do Problema -->
            <div class="flex flex-col gap-2">
                <label for="descricao" class="text-sm font-semibold text-gray-800">
                    Descrição do Problema *
                </label>
                <textarea
                    id="descricao"
                    name="descricao"
                    required
                    placeholder="Descreva em detalhes o problema encontrado"
                    class="focus:ring-senai-red w-full resize-none rounded border border-gray-400 px-4 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2"
                    rows="4"
                    ><?php echo e(old('descricao')); ?></textarea
                >
                <?php $__errorArgs = ['descricao'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-xs text-red-600"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Tipo de Chamado -->
            <div class="flex flex-col gap-2">
                <label for="tipo_chamado" class="text-sm font-semibold text-gray-800">
                    Tipo de Chamado *
                </label>
                <div class="relative w-56">
                    <select
                        id="tipo_chamado"
                        name="tipo_chamado"
                        required
                        class="focus:ring-senai-red w-full cursor-pointer appearance-none rounded border border-gray-400 bg-white px-4 py-2 pr-8 text-sm text-gray-700 focus:outline-none focus:ring-2"
                    >
                        <option
                            value=""
                            disabled
                            <?php echo e(old('tipo_chamado')
                                    ? ''
                                    : 'selected'); ?>

                            >Selecione
                        </option>
                        <option
                            value="interno"
                            <?php echo e(old('tipo_chamado') === 'interno'
                                    ? 'selected'
                                    : ''); ?>

                            >Interno
                        </option>
                        <option
                            value="externo"
                            <?php echo e(old('tipo_chamado') === 'externo'
                                    ? 'selected'
                                    : ''); ?>

                            >Externo
                        </option>
                    </select>
                    <span
                        class="pointer-events-none absolute inset-y-0 right-2 flex items-center text-gray-600"
                        >▼</span
                    >
                </div>
                <?php $__errorArgs = ['tipo_chamado'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-xs text-red-600"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Tipo de Incidente -->
            <div class="flex flex-col gap-2">
                <label for="id_tipo" class="text-sm font-semibold text-gray-800">
                    Tipo de Incidente:
                </label>
                <div class="relative w-56">
                    <select
                        id="id_tipo"
                        name="id_tipo"
                        required
                        class="focus:ring-senai-red w-full cursor-pointer appearance-none rounded border border-gray-400 bg-white px-4 py-2 pr-8 text-sm text-gray-700 focus:outline-none focus:ring-2"
                    >
                        <option value="" disabled <?php echo e(old('id_tipo') ? '' : 'selected'); ?>

                            >Selecione
                        </option>
                        <?php $__currentLoopData = $tipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option
                                value="<?php echo e($tipo->id_tipo); ?>"
                                <?php echo e(old('id_tipo') == $tipo->id_tipo
                                        ? 'selected'
                                        : ''); ?>

                            >
                                <?php echo e($tipo->categoria); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <span
                        class="pointer-events-none absolute inset-y-0 right-2 flex items-center text-gray-600"
                        >▼</span
                    >
                </div>
            </div>

            <!-- Local -->
            <div class="flex flex-col gap-2">
                <label for="id_local" class="text-sm font-semibold text-gray-800"> Local </label>
                <div class="relative w-56">
                    <select
                        id="id_local"
                        name="id_local"
                        required
                        class="focus:ring-senai-red w-full cursor-pointer appearance-none rounded border border-gray-400 bg-white px-4 py-2 pr-8 text-sm text-gray-700 focus:outline-none focus:ring-2"
                    >
                        <option value="" disabled <?php echo e(old('id_local') ? '' : 'selected'); ?>

                            >Selecione
                        </option>
                        <?php $__currentLoopData = $locais; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $local): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option
                                value="<?php echo e($local->id_local); ?>"
                                <?php echo e(old('id_local') == $local->id_local
                                        ? 'selected'
                                        : ''); ?>

                            >
                                <?php echo e($local->sala_setor); ?> - Bloco <?php echo e($local->bloco); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <span
                        class="pointer-events-none absolute inset-y-0 right-2 flex items-center text-gray-600"
                        >▼</span
                    >
                </div>
            </div>

            <!-- Seção Técnica (Apenas para não-alunos) -->
            <?php if (! (Auth::user()->isAluno())): ?>
            <div class="flex flex-col gap-2">
                <label for="secao_tecnica" class="text-sm font-semibold text-gray-800">
                    Seção Técnica
                </label>
                <div class="flex items-center gap-3">
                    <div class="relative w-56">
                        <select
                            id="secao_tecnica"
                            name="secao_tecnica"
                            class="focus:ring-senai-red w-full cursor-pointer appearance-none rounded border border-gray-400 bg-white px-4 py-2 pr-8 text-sm text-gray-700 focus:outline-none focus:ring-2"
                        >
                            <option
                                value=""
                                <?php echo e(old('secao_tecnica')
                                        ? ''
                                        : 'selected'); ?>

                                >Selecione
                            </option>
                            <option
                                value="eletrica"
                                <?php echo e(old('secao_tecnica') === 'eletrica'
                                        ? 'selected'
                                        : ''); ?>

                                >Elétrica
                            </option>
                            <option
                                value="hidraulica"
                                <?php echo e(old('secao_tecnica') === 'hidraulica'
                                        ? 'selected'
                                        : ''); ?>

                                >Hidráulica
                            </option>
                            <option
                                value="civil"
                                <?php echo e(old('secao_tecnica') === 'civil'
                                        ? 'selected'
                                        : ''); ?>

                                >Civil
                            </option>
                            <option
                                value="mecanica"
                                <?php echo e(old('secao_tecnica') === 'mecanica'
                                        ? 'selected'
                                        : ''); ?>

                                >Mecânica
                            </option>
                        </select>
                        <span
                            class="pointer-events-none absolute inset-y-0 right-2 flex items-center text-gray-600"
                            >▼</span
                        >
                    </div>
                    <span class="text-xs text-gray-500">(Elétrica, Hidraulica, Civil, etc)</span>
                </div>
            </div>
            <?php endif; ?>

            <!-- Nível de Prioridade (Apenas para não-alunos) -->
            <?php if (! (Auth::user()->isAluno())): ?>
            <div class="flex flex-col gap-2">
                <label for="prioridade" class="text-sm font-semibold text-gray-800">
                    Nível de Prioridade
                </label>
                <div class="flex items-center gap-3">
                    <div class="relative w-56">
                        <select
                            id="prioridade"
                            name="prioridade"
                            required
                            class="focus:ring-senai-red w-full cursor-pointer appearance-none rounded border border-gray-400 bg-white px-4 py-2 pr-8 text-sm text-gray-700 focus:outline-none focus:ring-2"
                        >
                            <option
                                value=""
                                disabled
                                <?php echo e(old('prioridade')
                                        ? ''
                                        : 'selected'); ?>

                                >Selecione
                            </option>
                            <option
                                value="baixa"
                                <?php echo e(old('prioridade') === 'baixa'
                                        ? 'selected'
                                        : ''); ?>

                                >Baixa
                            </option>
                            <option
                                value="media"
                                <?php echo e(old('prioridade') === 'media'
                                        ? 'selected'
                                        : ''); ?>

                                >Média
                            </option>
                            <option
                                value="alta"
                                <?php echo e(old('prioridade') === 'alta'
                                        ? 'selected'
                                        : ''); ?>

                                >Alta
                            </option>
                        </select>
                        <span
                            class="pointer-events-none absolute inset-y-0 right-2 flex items-center text-gray-600"
                            >▼</span
                        >
                    </div>
                    <span class="text-xs text-gray-500">(Baixa, Média, Alta, Crítica)</span>
                </div>
            </div>
            <?php endif; ?>

            <!-- Nível de Complexidade (Apenas para não-alunos) -->
            <?php if (! (Auth::user()->isAluno())): ?>
            <div class="flex flex-col gap-2">
                <label for="complexidade" class="text-sm font-semibold text-gray-800">
                    Nível de Complexidade
                </label>
                <div class="flex items-center gap-3">
                    <div class="relative w-56">
                        <select
                            id="complexidade"
                            name="complexidade"
                            class="focus:ring-senai-red w-full cursor-pointer appearance-none rounded border border-gray-400 bg-white px-4 py-2 pr-8 text-sm text-gray-700 focus:outline-none focus:ring-2"
                        >
                            <option
                                value=""
                                <?php echo e(old('complexidade')
                                        ? ''
                                        : 'selected'); ?>

                                >Selecione
                            </option>
                            <option
                                value="simples"
                                <?php echo e(old('complexidade') === 'simples'
                                        ? 'selected'
                                        : ''); ?>

                                >Simples
                            </option>
                            <option
                                value="media"
                                <?php echo e(old('complexidade') === 'media'
                                        ? 'selected'
                                        : ''); ?>

                                >Média
                            </option>
                            <option
                                value="complexa"
                                <?php echo e(old('complexidade') === 'complexa'
                                        ? 'selected'
                                        : ''); ?>

                                >Complexa
                            </option>
                        </select>
                        <span
                            class="pointer-events-none absolute inset-y-0 right-2 flex items-center text-gray-600"
                            >▼</span
                        >
                    </div>
                    <span class="text-xs text-gray-500">(Simples, Média, Complexa)</span>
                </div>
            </div>
            <?php endif; ?>

            <!-- Tipo de Trabalho (Apenas para não-alunos) -->
            <?php if (! (Auth::user()->isAluno())): ?>
            <div class="flex flex-col gap-2">
                <label for="tipo_trabalho" class="text-sm font-semibold text-gray-800">
                    Tipo de Trabalho
                </label>
                <div class="flex items-center gap-3">
                    <div class="relative w-56">
                        <select
                            id="tipo_trabalho"
                            name="tipo_trabalho"
                            class="focus:ring-senai-red w-full cursor-pointer appearance-none rounded border border-gray-400 bg-white px-4 py-2 pr-8 text-sm text-gray-700 focus:outline-none focus:ring-2"
                        >
                            <option
                                value=""
                                <?php echo e(old('tipo_trabalho')
                                        ? ''
                                        : 'selected'); ?>

                                >Selecione
                            </option>
                            <option
                                value="preventiva"
                                <?php echo e(old('tipo_trabalho') === 'preventiva'
                                        ? 'selected'
                                        : ''); ?>

                                >Preventiva
                            </option>
                            <option
                                value="corretiva"
                                <?php echo e(old('tipo_trabalho') === 'corretiva'
                                        ? 'selected'
                                        : ''); ?>

                                >Corretiva
                            </option>
                            <option
                                value="melhoria"
                                <?php echo e(old('tipo_trabalho') === 'melhoria'
                                        ? 'selected'
                                        : ''); ?>

                                >Melhoria
                            </option>
                        </select>
                        <span
                            class="pointer-events-none absolute inset-y-0 right-2 flex items-center text-gray-600"
                            >▼</span
                        >
                    </div>
                    <span class="text-xs text-gray-500">(Preventiva, Corretiva, Melhoria)</span>
                </div>
            </div>
            <?php endif; ?>

            <!-- Mensagem para Alunos -->
            <?php if(Auth::user()->isAluno()): ?>
            <div class="rounded border border-blue-300 bg-blue-50 px-4 py-3 text-xs text-blue-700">
                <p><strong>ℹ️ Informação:</strong> Alunos não podem definir Prioridade, Seção Técnica, Complexidade ou Tipo de Trabalho. Estes campos serão preenchidos automaticamente pela equipe de manutenção.</p>
            </div>
            <?php endif; ?>

            <!-- Foto -->
            <div class="flex flex-col gap-2">
                <label class="text-sm font-semibold text-gray-800">Foto</label>
                <div
                    id="drop-zone"
                    class="hover:border-senai-red relative flex h-24 w-24 cursor-pointer flex-col items-center justify-center rounded border-2 border-dashed border-gray-400 transition hover:bg-red-50"
                >
                    <input
                        id="foto"
                        name="foto"
                        type="file"
                        accept="image/*"
                        class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
                    />
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6 text-gray-400"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                        />
                    </svg>
                    <p class="px-1 text-center text-xs leading-tight text-gray-500">Faça upload</p>
                    <p class="mt-1 px-1 text-center text-xs text-gray-400">ou arraste uma imagem</p>
                </div>
                <div id="preview-container" class="mt-2 hidden">
                    <img
                        id="preview-img"
                        src=""
                        alt="Pré-visualização"
                        class="max-h-32 rounded border border-gray-300 shadow-sm"
                    />
                    <button
                        type="button"
                        id="remove-img"
                        class="text-senai-red mt-1 text-xs hover:underline"
                    >
                        Remover imagem
                    </button>
                </div>
            </div>

            <!-- Botão Enviar -->
            <div class="pt-3">
                <button
                    type="submit"
                    class="bg-senai-red focus:ring-senai-red rounded px-8 py-3 text-sm font-bold text-white transition duration-200 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-95"
                >
                    Enviar
                </button>
            </div>
        </form>
    </main>

    <!-- Rodapé -->
    <footer class="bg-senai-red mt-8">
        <div class="mx-auto grid max-w-5xl grid-cols-1 gap-8 px-6 py-8 md:grid-cols-2">
            <div class="text-white">
                <h3 class="mb-3 text-sm font-bold uppercase tracking-wide">Edifício Sede FIESP</h3>
                <p class="text-sm leading-relaxed text-red-100">Av. Paulista, 1313, São Paulo/SP<br />CEP 01311-923</p>
            </div>
            <div class="text-white">
                <h3 class="mb-3 text-sm font-bold uppercase tracking-wide">
                    Central de Relacionamento
                </h3>
                <p class="text-sm leading-relaxed text-red-100">(11) 3322-0050 (Telefone/WhatsApp)<br />
                0800-055-1000 (Interior de SP,<br />somente telefone fixo)</p>
            </div>
        </div>
        <div class="bg-red-900 py-3 text-center text-xs text-red-200">
            Copyright 2026 &copy; Todos os direitos reservados.
        </div>
    </footer>

    <script>
        const input = document.getElementById('foto');
        const previewContainer = document.getElementById('preview-container');
        const previewImg = document.getElementById('preview-img');
        const removeBtn = document.getElementById('remove-img');

        input.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    previewImg.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
        removeBtn.addEventListener('click', () => {
            input.value = '';
            previewImg.src = '';
            previewContainer.classList.add('hidden');
        });

        const zone = document.getElementById('drop-zone');
        zone.addEventListener('dragover', (e) => {
            e.preventDefault();
            zone.classList.add('border-senai-red');
        });
        zone.addEventListener('dragleave', () => zone.classList.remove('border-senai-red'));
        zone.addEventListener('drop', (e) => {
            e.preventDefault();
            zone.classList.remove('border-senai-red');
            const file = e.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                const dt = new DataTransfer();
                dt.items.add(file);
                input.files = dt.files;
                input.dispatchEvent(new Event('change'));
            }
        });
    </script>
</body>
</html>
<?php /**PATH C:\laragon\www\PredialFix\projeto\frontend\resources\views/chamados/create.blade.php ENDPATH**/ ?>