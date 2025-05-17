<?php

return [
    // Auto-assign new tickets to customer service representatives
    'auto_assign' => true,
    
    // Default ticket priority for new tickets
    'default_priority' => 'medium',
    
    // Enable/disable internal notes on tickets
    'enable_internal_notes' => true,
    
    // Time in days before resolved tickets are automatically closed
    'auto_close_days' => 3,
    
    // System notification settings
    'notifications' => [
        'email' => [
            'enabled' => true,
            'notify_customer_on_reply' => true,
            'notify_agent_on_assignment' => true,
            'notify_agent_on_new_ticket' => true,
        ]
    ],
];
?>