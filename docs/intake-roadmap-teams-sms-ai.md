# Intake roadmap: Teams, SMS, and AI

## Iteration 1: Keycloak/AD + SMTP/IMAP

Current work establishes Keycloak/AD-ready SSO, group-based authorization foundations, generic IMAP/SMTP email intake, and shared intake channel/message tables.

## Iteration 2: Offline/sovereign AI triage

Expand `ai_provider_settings` into real provider integrations. Do not call AI from the current foundation. Future agent hooks should support:

- Summarize request
- Classify ticket
- Ask requester for missing information before ticket creation
- Recommend a knowledge-base article
- Draft analyst response
- Execute an approved L1 runbook action only with explicit approval and auditing

## Iteration 3: Teams intake

Design goal: users message a Teams channel or bot, then AI can ask clarifying questions before ticket creation. This is not implemented yet.

Microsoft Graph webhooks require Microsoft Graph to reach the FreeITSM endpoint. In segregated environments, polling or a controlled relay may be needed.

## Iteration 4 optional: SMS intake

SMS should remain optional and target edge users with poor connectivity. Future gateways may include:

- Twilio-like HTTP provider
- Email-to-SMS mailbox
- SMPP gateway
- Local GSM modem bridge

The intake abstraction should let these channels write `intake_messages` first, then follow the same triage/ticket creation lifecycle.
