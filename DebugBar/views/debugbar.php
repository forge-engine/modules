<?php
/**
 * @var array $data
 */

use Forge\Modules\DebugBar\Collectors\TimeCollector;

$startTime = TimeCollector::getStartTime();
?>
<div class="forge-debugbar">
    <div class="forge-debugbar-logo">Forge</div>

    <div class="forge-debugbar-metrics">
        <div class="forge-debugbar-tab " data-tab="messages">Messages (<?= count($data['messages']) ?>)</div>
        <div class="forge-debugbar-tab" data-tab="timeline">Timeline (<?= count($data['timeline']) ?>)</div>
        <div class="forge-debugbar-tab" data-tab="exceptions">Exceptions (<?= count($data['exceptions']) ?>)</div>
        <div class="forge-debugbar-tab" data-tab="views">Views</div>
        <div class="forge-debugbar-tab" data-tab="route">Route</div>
        <!--<div class="forge-debugbar-tab" data-tab="queries">Queries</div>-->
        <!--<div class="forge-debugbar-tab" data-tab="mails">Mails</div>-->
        <!--<div class="forge-debugbar-tab" data-tab="auth">Auth</div>-->
        <!--<div class="forge-debugbar-tab" data-tab="gate">Gate</div>-->
        <div class="forge-debugbar-tab" data-tab="session">Session</div>
        <div class="forge-debugbar-tab" data-tab="request">Request</div>
    </div>

    <div class="forge-debugbar-item">
        Time: <span class="forge-debugbar-item-value" id="debugbar-time"><?= $data['time'] ?? 'N/A' ?></span>
    </div>
    <div class="forge-debugbar-item">
        Memory: <span class="forge-debugbar-item-value" id="debugbar-memory"><?= $data['memory'] ?? 'N/A' ?></span>
    </div>
</div>

<div class="forge-debugbar-panels">
    <div class="forge-debugbar-panel" id="debugbar-panel-messages">
        <?php if (isset($data['messages']) && is_array($data['messages']) && !empty($data['messages'])): ?>
            <ol class="debugbar-messages-list">
                <?php foreach ($data['messages'] as $message): ?>
                    <li class="debugbar-message-item">
                    <span class="debugbar-message-time">
                        [<?= number_format(($message['time'] - $startTime) * 1000, 2) ?>ms]
                    </span>
                        <strong class="debugbar-message-name"><?= htmlspecialchars($message['message']) ?></strong>
                        <?php if (in_array($message['label'], ['info', 'warning', 'error'])): ?>
                            <span
                                class="debugbar-message-label debugbar-message-label-<?= htmlspecialchars($message['label']) ?>">
                            <?= htmlspecialchars(ucfirst($message['label'])) ?>
                        </span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ol>
        <?php else: ?>
            <p>No messages collected.</p>
        <?php endif; ?>
    </div>


    <div class="forge-debugbar-panel" id="debugbar-panel-timeline">
        <?php if (isset($data['timeline']) && is_array($data['timeline']) && !empty($data['timeline'])): ?>
            <ol class="debugbar-timeline-list">
                <?php foreach ($data['timeline'] as $event): ?>
                    <li class="debugbar-timeline-item">
                    <span class="debugbar-timeline-time">
                        [<?= number_format(($event['time'] - $startTime) * 1000, 2) ?>ms]
                    </span>
                        <strong class="debugbar-timeline-name"><?= htmlspecialchars($event['name']) ?></strong>
                        <?php if (in_array($event['label'], ['info', 'warning', 'error', 'start', 'end'])): ?>
                            <span
                                class="debugbar-timeline-label debugbar-timeline-label-<?= htmlspecialchars($event['label']) ?>">
                            <?= htmlspecialchars(ucfirst($event['label'])) ?>
                        </span>
                        <?php endif; ?>
                        <?php if (!empty($event['data'])): ?>
                            <details>
                                <summary>Data</summary>
                                <pre
                                    class="debugbar-timeline-data"><?= htmlspecialchars(print_r($event['data'], true)) ?></pre>
                            </details>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ol>
        <?php else: ?>
            <p>No timeline events recorded.</p>
        <?php endif; ?>
    </div>


    <div class="forge-debugbar-panel" id="debugbar-panel-exceptions">
        <?php if (isset($data['exceptions']) && is_array($data['exceptions']) && !empty($data['exceptions'])): ?>
            <ol class="debugbar-exceptions-list">
                <?php foreach ($data['exceptions'] as $exception): ?>
                    <li class="debugbar-exception-item">
                        <strong>Type:</strong> <?= htmlspecialchars($exception['type']) ?><br/>
                        <strong>Message:</strong> <?= htmlspecialchars($exception['message']) ?><br/>
                        <strong>Code:</strong> <?= htmlspecialchars($exception['code']) ?><br/>
                        <strong>File:</strong> <?= htmlspecialchars($exception['file']) ?><br/>
                        <?php if (!empty($exception['trace'])): ?>
                            <details>
                                <summary>Trace</summary>
                                <ul class="debugbar-exception-trace-list">
                                    <?php foreach (explode("\n", $exception['trace']) as $traceLine): ?>
                                        <li class="debugbar-exception-trace-item">
                                            <?= htmlspecialchars($traceLine) ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </details>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ol>
        <?php else: ?>
            <p>No exceptions caught.</p>
        <?php endif; ?>
    </div>

    <div class="forge-debugbar-panel" id="debugbar-panel-views">
        <?php if (isset($data['views']) && is_array($data['views']) && !empty($data['views'])): ?>
            <ol class="debugbar-views-list">
                <?php foreach ($data['views'] as $view): ?>
                    <li class="debugbar-view-item">
                        <strong>Path:</strong> <?= htmlspecialchars($view['path']) ?>
                        <?php if (!empty($view['data'])): ?>
                            <details>
                                <summary>Data</summary>
                                <ul class="debugbar-view-data-list">
                                    <?php foreach ($view['data'] as $key => $value): ?>
                                        <li class="debugbar-view-data-item">
                                            <strong><?= htmlspecialchars($key) ?>:</strong>
                                            <?php if (is_array($value)): ?>
                                                <ul class="debugbar-view-data-sublist">
                                                    <?php foreach ($value as $subKey => $subValue): ?>
                                                        <li class="debugbar-view-data-subitem">
                                                            <strong><?= htmlspecialchars($subKey) ?>:</strong>
                                                            <?= is_array($subValue) ? htmlspecialchars(print_r($subValue, true)) : htmlspecialchars($subValue) ?>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php else: ?>
                                                <?= htmlspecialchars($value) ?>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </details>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ol>
        <?php else: ?>
            <p>No views rendered.</p>
        <?php endif; ?>
    </div>


    <div class="forge-debugbar-panel" id="debugbar-panel-route">
        <?php if (isset($data['route']) && is_array($data['route']) && !empty($data['route'])): ?>
            <ol class="debugbar-route-list">
                <?php foreach ($data['route'] as $route): ?>
                    <li class="debugbar-route-item">
                    <span class="debugbar-route-method">
                        [<?= htmlspecialchars($route['method']) ?>]
                    </span>
                        <strong><?= htmlspecialchars($route['uri']) ?></strong>
                        <span class="debugbar-route-handler">
                        <?= htmlspecialchars($route['handler']) ?>
                    </span>
                        <?php if (!empty($route['middleware'])): ?>
                            <details>
                                <summary>Middleware</summary>
                                <ul class="debugbar-route-middleware-list">
                                    <?php foreach ($route['middleware'] as $middleware): ?>
                                        <li class="debugbar-route-middleware-item">
                                            <?= htmlspecialchars($middleware) ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </details>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ol>
        <?php else: ?>
            <p>No route information available.</p>
        <?php endif; ?>
    </div>


    <div class="forge-debugbar-panel" id="debugbar-panel-queries">
        <p>Queries Panel Content here.</p>
    </div>

    <div class="forge-debugbar-panel" id="debugbar-panel-mails">
        <p>Mails Panel Content here.</p>
    </div>

    <div class="forge-debugbar-panel" id="debugbar-panel-auth">
        <p>Auth Panel Content here.</p>
    </div>

    <div class="forge-debugbar-panel" id="debugbar-panel-gate">
        <p>Gate Panel Content here.</p>
    </div>

    <div class="forge-debugbar-panel" id="debugbar-panel-session">
        <?php if (isset($data['session']) && is_array($data['session']) && !empty($data['session'])): ?>
            <ol class="debugbar-session-list">
                <?php foreach ($data['session'] as $key => $value): ?>
                    <li class="debugbar-session-item">
                        <strong><?= htmlspecialchars($key) ?>:</strong> <?= htmlspecialchars($value) ?>
                    </li>
                <?php endforeach; ?>
            </ol>
        <?php else: ?>
            <p>No session data available or session not active.</p>
        <?php endif; ?>
    </div>


    <div class="forge-debugbar-panel" id="debugbar-panel-request">
        <?php if (isset($data['request']) && is_array($data['request']) && !empty($data['request'])): ?>
            <ol class="debugbar-request-list">
                <li class="debugbar-request-item">
                    <strong>URL:</strong> <?= htmlspecialchars($data['request']['url']) ?>
                </li>
                <li class="debugbar-request-item">
                    <strong>Method:</strong> <?= htmlspecialchars($data['request']['method']) ?>
                </li>
                <li class="debugbar-request-item">
                    <strong>IP:</strong> <?= htmlspecialchars($data['request']['ip']) ?>
                </li>
                <li class="debugbar-request-item">
                    <strong>Headers:</strong>
                    <?php if (!empty($data['request']['headers'])): ?>
                        <ul class="debugbar-request-headers-list">
                            <?php foreach ($data['request']['headers'] as $header => $value): ?>
                                <li class="debugbar-request-header-item">
                                    <strong><?= htmlspecialchars($header) ?>:</strong> <?= htmlspecialchars($value) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>No headers available.</p>
                    <?php endif; ?>
                </li>
                <li class="debugbar-request-item">
                    <strong>Query:</strong>
                    <?php if (!empty($data['request']['query'])): ?>
                        <pre
                            class="debugbar-request-query"><?= htmlspecialchars(print_r($data['request']['query'], true)) ?></pre>
                    <?php else: ?>
                        <p>No query parameters available.</p>
                    <?php endif; ?>
                </li>
                <li class="debugbar-request-item">
                    <strong>Body:</strong>
                    <?php if (!empty($data['request']['body'])): ?>
                        <pre
                            class="debugbar-request-body"><?= htmlspecialchars(print_r($data['request']['body'], true)) ?></pre>
                    <?php else: ?>
                        <p>No body data available.</p>
                    <?php endif; ?>
                </li>
                <li class="debugbar-request-item">
                    <strong>Cookies:</strong>
                    <?php if (!empty($data['request']['cookies'])): ?>
                        <ul class="debugbar-request-cookies-list">
                            <?php foreach ($data['request']['cookies'] as $cookie => $value): ?>
                                <li class="debugbar-request-cookie-item">
                                    <strong><?= htmlspecialchars($cookie) ?>:</strong> <?= htmlspecialchars($value) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>No cookies available.</p>
                    <?php endif; ?>
                </li>
                <li class="debugbar-request-item">
                    <strong>Files:</strong>
                    <?php if (!empty($data['request']['files'])): ?>
                        <pre
                            class="debugbar-request-files"><?= htmlspecialchars(print_r($data['request']['files'], true)) ?></pre>
                    <?php else: ?>
                        <p>No files uploaded.</p>
                    <?php endif; ?>
                </li>
            </ol>
        <?php else: ?>
            <p>No request information available.</p>
        <?php endif; ?>
    </div>

</div>