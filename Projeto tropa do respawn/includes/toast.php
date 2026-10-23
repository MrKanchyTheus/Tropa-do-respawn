<?php
/**
 * Componente de Toast - Notificações Flutuantes
 * Atende ao requisito: "Retorne sempre as mensagens de erros de forma claras na interface para o usuário (TOAST)"
 */
?>
<!-- Container Flutuante para Toasts -->
<div id="toast-container" class="fixed top-5 right-5 z-[9999] flex flex-col gap-3 pointer-events-auto"></div>

<?php if (!empty($_SESSION['toast'])): ?>
    <?php
        $toastTipo = $_SESSION['toast']['type'] ?? 'info';
        $toastMsg = $_SESSION['toast']['message'] ?? '';
        unset($_SESSION['toast']); // Limpa após exibir
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            exibirToast(<?= json_encode($toastTipo) ?>, <?= json_encode($toastMsg) ?>);
        });
    </script>
<?php endif; ?>
