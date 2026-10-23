/**
 * Tropa do Respawn - Scripts do Frontend
 * Regras:
 * - Retorne sempre as mensagens de erros de forma claras na interface para o usuário (TOAST)
 * - sempre trate as mensagens de caixa de mensagens nativas do navegador em um MODAL
 */

// Sistema de Modal Customizado para substituir alertas e confirmações nativas do navegador
const TropaModal = {
    modalEl: null,
    titleEl: null,
    bodyEl: null,
    confirmBtn: null,
    cancelBtn: null,
    closeBtn: null,

    init() {
        this.modalEl = document.getElementById('tropa-modal');
        if (!this.modalEl) return;

        this.titleEl = document.getElementById('tropa-modal-title');
        this.bodyEl = document.getElementById('tropa-modal-body');
        this.confirmBtn = document.getElementById('tropa-modal-confirm');
        this.cancelBtn = document.getElementById('tropa-modal-cancel');
        this.closeBtn = document.getElementById('tropa-modal-close');

        if (this.closeBtn) {
            this.closeBtn.addEventListener('click', () => this.fechar());
        }
        if (this.cancelBtn) {
            this.cancelBtn.addEventListener('click', () => this.fechar());
        }
        this.modalEl.addEventListener('click', (e) => {
            if (e.target === this.modalEl) this.fechar();
        });
    },

    abrir(titulo, mensagem, tipo = 'info', onConfirm = null) {
        if (!this.modalEl) this.init();
        if (!this.modalEl) return;

        this.titleEl.textContent = titulo;
        this.bodyEl.innerHTML = mensagem;

        if (onConfirm && typeof onConfirm === 'function') {
            this.cancelBtn.classList.remove('hidden');
            this.confirmBtn.textContent = 'Confirmar';
            this.confirmBtn.onclick = () => {
                this.fechar();
                onConfirm();
            };
        } else {
            this.cancelBtn.classList.add('hidden');
            this.confirmBtn.textContent = 'Entendi';
            this.confirmBtn.onclick = () => this.fechar();
        }

        this.modalEl.classList.remove('hidden');
        this.modalEl.classList.add('flex');
    },

    fechar() {
        if (!this.modalEl) return;
        this.modalEl.classList.add('hidden');
        this.modalEl.classList.remove('flex');
    },

    alert(titulo, mensagem) {
        this.abrir(titulo, mensagem, 'alert');
    },

    confirm(titulo, mensagem, onConfirm) {
        this.abrir(titulo, mensagem, 'confirm', onConfirm);
    }
};

// Sobrescreve o alert nativo do navegador para usar sempre o MODAL gamer personalizado
window.alert = function(mensagem, titulo = 'Aviso da Tropa') {
    TropaModal.alert(titulo, mensagem);
};

// Sistema de Toasts (Notificações Flutuantes)
function exibirToast(tipo, mensagem) {
    const container = document.getElementById('toast-container');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = 'toast-enter flex items-center gap-3 px-4 py-3 rounded-xl border shadow-xl max-w-md w-full backdrop-blur-md transition-all';

    let icon = '';
    let borderClass = '';
    let bgClass = '';
    let textClass = 'text-white';

    if (tipo === 'success') {
        borderClass = 'border-green-500/50';
        bgClass = 'bg-[#0f1712]/95 text-green-300';
        icon = `<svg class="w-6 h-6 text-green-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>`;
    } else if (tipo === 'error') {
        borderClass = 'border-red-500/60 shadow-[0_0_15px_rgba(239,68,68,0.25)]';
        bgClass = 'bg-[#180e12]/95 text-red-300';
        icon = `<svg class="w-6 h-6 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`;
    } else if (tipo === 'warning') {
        borderClass = 'border-amber-500/50';
        bgClass = 'bg-[#18150c]/95 text-amber-300';
        icon = `<svg class="w-6 h-6 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>`;
    } else {
        borderClass = 'border-purple-500/50 shadow-[0_0_15px_rgba(168,85,247,0.25)]';
        bgClass = 'bg-[#140e20]/95 text-purple-300';
        icon = `<svg class="w-6 h-6 text-purple-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`;
    }

    toast.className += ` ${borderClass} ${bgClass}`;
    toast.innerHTML = `
        ${icon}
        <div class="flex-1 text-sm font-medium ${textClass}">${mensagem}</div>
        <button type="button" class="text-gray-400 hover:text-white ml-2 text-lg shrink-0">&times;</button>
    `;

    toast.querySelector('button').addEventListener('click', () => {
        toast.classList.remove('toast-enter');
        toast.classList.add('toast-exit');
        setTimeout(() => toast.remove(), 350);
    });

    container.appendChild(toast);

    // Auto-remove após 4.5 segundos
    setTimeout(() => {
        if (toast.parentElement) {
            toast.classList.remove('toast-enter');
            toast.classList.add('toast-exit');
            setTimeout(() => toast.remove(), 350);
        }
    }, 4500);
}

// Inicialização de componentes quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => {
    TropaModal.init();

    // Toggle para visualização de senhas (olhinho)
    document.querySelectorAll('[data-toggle-password]').forEach(button => {
        button.addEventListener('click', () => {
            const targetId = button.getAttribute('data-toggle-password');
            const input = document.getElementById(targetId);
            if (!input) return;

            if (input.type === 'password') {
                input.type = 'text';
                button.classList.add('text-purple-400');
            } else {
                input.type = 'password';
                button.classList.remove('text-purple-400');
            }
        });
    });

    // Confirmação para ações destrutivas (excluir post, sair, etc.)
    document.querySelectorAll('[data-confirm-modal]').forEach(element => {
        element.addEventListener('click', (e) => {
            e.preventDefault();
            const mensagem = element.getAttribute('data-confirm-modal') || 'Tem certeza que deseja prosseguir com esta ação?';
            const titulo = element.getAttribute('data-confirm-title') || 'Confirmar Ação';
            const url = element.getAttribute('href') || element.getAttribute('data-url');
            const form = element.closest('form');

            TropaModal.confirm(titulo, mensagem, () => {
                if (form && element.type === 'submit') {
                    form.submit();
                } else if (url) {
                    window.location.href = url;
                }
            });
        });
    });

    // Menu Mobile Drawer
    const mobileMenuBtn = document.getElementById('btn-mobile-menu');
    const mobileMenuDrawer = document.getElementById('mobile-drawer');
    const mobileCloseBtn = document.getElementById('btn-close-mobile');

    if (mobileMenuBtn && mobileMenuDrawer) {
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenuDrawer.classList.remove('hidden');
        });
    }
    if (mobileCloseBtn && mobileMenuDrawer) {
        mobileCloseBtn.addEventListener('click', () => {
            mobileMenuDrawer.classList.add('hidden');
        });
    }

    // Interatividade nas Estrelas de Avaliação
    const starContainers = document.querySelectorAll('.star-rating-input');
    starContainers.forEach(container => {
        const stars = container.querySelectorAll('.rating-star');
        const input = container.querySelector('input[type="hidden"]');

        stars.forEach(star => {
            star.addEventListener('mouseenter', () => {
                const val = parseInt(star.getAttribute('data-value'), 10);
                highlightStars(stars, val);
            });

            star.addEventListener('click', () => {
                const val = parseInt(star.getAttribute('data-value'), 10);
                if (input) input.value = val;
                highlightStars(stars, val);
            });
        });

        container.addEventListener('mouseleave', () => {
            const currentVal = input ? parseInt(input.value, 10) || 0 : 0;
            highlightStars(stars, currentVal);
        });
    });

    function highlightStars(stars, value) {
        stars.forEach(s => {
            const sVal = parseInt(s.getAttribute('data-value'), 10);
            if (sVal <= value) {
                s.classList.remove('text-gray-600');
                s.classList.add('text-purple-400');
            } else {
                s.classList.add('text-gray-600');
                s.classList.remove('text-purple-400');
            }
        });
    }
});
