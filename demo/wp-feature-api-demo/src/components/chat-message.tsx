/**
 * WordPress dependencies
 */
import { Spinner } from '@wordpress/components';

/**
 * External dependencies
 */
import Markdown from 'react-markdown';

interface MessageProps {
	text: string;
}

export const UserMessage = ( { text }: MessageProps ) => (
	<div className="demo-chat-message demo-chat-message-user">
		<Markdown>{ text }</Markdown>
	</div>
);

export const AssistantMessage = ( { text }: MessageProps ) => (
	<div className="demo-chat-message demo-chat-message-assistant">
		<Markdown>{ text }</Markdown>
	</div>
);

export const PendingAssistantMessage = () => (
	<div className="demo-chat-message demo-chat-message-assistant demo-chat-message-pending">
		<Spinner />
	</div>
);
