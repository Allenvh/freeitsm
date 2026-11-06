<?php
/**
 * The "add this calendar to your phone" dialogue — shared markup.
 *
 * Used by the Calendar module (the shared team calendar) and by Preferences
 * (an analyst's own scheduled work, GH #75). They hand out different feeds but
 * the act is identical: here is a URL, here is a QR code, here is how to add it
 * on iOS and Android, and here is how to revoke it.
 *
 * 🔑 LABELS ARE PASSED IN RATHER THAN LOOKED UP HERE. The two callers live in
 * different translation namespaces (calendar.* and system.*), and moving the
 * strings into a shared namespace would have silently untranslated them in every
 * locale that had already translated the originals. The markup is shared; the
 * words stay where they are.
 *
 * ⚠️ Behaviour lives in assets/js/subscribe.js — load it, and qrcode.min.js.
 */

/**
 * Prints the "subscribe to this calendar feed" dialogue - feed address, QR code and phone
 * instructions - with the labels given.
 *
 * @param string $id       prefix for every element id, so two can coexist
 * @param array  $labels   title, intro, address_label, address_hint, url_label,
 *                         copy, ios_label, ios_hint, android_label, android_hint,
 *                         reset, close, insecure, secret_note
 */
function renderSubscribeModal(string $id, array $labels): void
{
    $e = fn($k) => htmlspecialchars($labels[$k] ?? '');
    ?>
    <div class="modal" id="<?php echo $id; ?>Modal">
        <div class="modal-content subscribe-modal">
            <div class="modal-header"><?php echo $e('title'); ?></div>
            <div class="modal-body">
                <p class="subscribe-intro"><?php echo $e('intro'); ?></p>

                <?php /* Shown only over plain HTTP, and it is the reason this was
                         worth sharing: the Calendar module's own subscribe dialogue
                         never warned about this, and its feed has the same exposure.
                         The token in the URL is the ONLY thing protecting the
                         contents, and without TLS it crosses the network in clear
                         on every refresh, several times a day, indefinitely. */ ?>
                <div class="subscribe-warn" id="<?php echo $id; ?>Insecure" style="display:none;">
                    <?php echo $e('insecure'); ?>
                </div>

                <div class="form-group">
                    <label class="form-label"><?php echo $e('address_label'); ?></label>
                    <input type="text" class="form-input" id="<?php echo $id; ?>Host"
                           autocomplete="off" autocapitalize="off" spellcheck="false">
                    <p class="form-hint"><?php echo $e('address_hint'); ?></p>
                </div>

                <div class="subscribe-qr" id="<?php echo $id; ?>Qr"></div>

                <div class="form-group">
                    <label class="form-label"><?php echo $e('url_label'); ?></label>
                    <div class="subscribe-url-row">
                        <input type="text" id="<?php echo $id; ?>Url" class="form-input subscribe-url" readonly value="">
                        <button type="button" class="btn btn-secondary btn-sm"
                                onclick="FreeITSMSubscribe.copy('<?php echo $id; ?>')"><?php echo $e('copy'); ?></button>
                    </div>
                </div>

                <?php if (!empty($labels['secret_note'])): ?>
                <p class="subscribe-hint subscribe-secret"><?php echo $e('secret_note'); ?></p>
                <?php endif; ?>

                <p class="subscribe-hint"><strong><?php echo $e('ios_label'); ?>:</strong> <?php echo $e('ios_hint'); ?></p>
                <p class="subscribe-hint"><strong><?php echo $e('android_label'); ?>:</strong> <?php echo $e('android_hint'); ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="subscribe-reset"
                        onclick="FreeITSMSubscribe.reset('<?php echo $id; ?>')"><?php echo $e('reset'); ?></button>
                <div class="modal-footer-right">
                    <button class="btn btn-secondary"
                            onclick="FreeITSMSubscribe.close('<?php echo $id; ?>')"><?php echo $e('close'); ?></button>
                </div>
            </div>
        </div>
    </div>
    <?php
}
