<?php
/**
 * Componente de Modal Gamer Customizado
 * Atende ao requisito: "sempre trate as mensagens de caixa de mensagens nativas do navegador em um MODAL"
 */
?>
<!-- Modal Gamer Universal -->
<div id="tropa-modal" class="fixed inset-0 z-[9990] hidden items-center justify-center bg-black/80 backdrop-blur-md p-4 transition-opacity">
    <div class="relative w-full max-w-md bg-dark-900 border border-purple-500/40 rounded-2xl p-6 shadow-[0_0_40px_rgba(147,51,234,0.35)] animate-in fade-in zoom-in duration-200">
        
        <!-- Topo do Modal com Ícone e Fechar -->
        <div class="flex items-center justify-between pb-4 border-b border-purple-900/40">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-950/80 border border-purple-500/40 flex items-center justify-center text-purple-400">
                    <i class="fa-solid fa-gamepad text-lg"></i>
                </div>
                <h3 id="tropa-modal-title" class="text-xl font-rajdhani font-bold text-white tracking-wide">
                    Aviso da Tropa
                </h3>
            </div>
            <button id="tropa-modal-close" type="button" class="text-gray-400 hover:text-white transition-colors p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Conteúdo do Modal -->
        <div id="tropa-modal-body" class="py-5 text-gray-300 text-sm leading-relaxed">
            Mensagem do modal...
        </div>

        <!-- Botões de Ação -->
        <div class="flex items-center justify-end gap-3 pt-3 border-t border-purple-900/30">
            <button id="tropa-modal-cancel" type="button" class="hidden px-4 py-2 rounded-xl text-sm font-medium text-gray-300 hover:text-white hover:bg-dark-800 transition-all border border-gray-700/50">
                Cancelar
            </button>
            <button id="tropa-modal-confirm" type="button" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 shadow-[0_0_20px_rgba(168,85,247,0.4)] transition-all">
                Confirmar
            </button>
        </div>
    </div>
</div>
