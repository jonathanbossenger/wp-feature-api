/**
 * WordPress dependencies
 */
import { useState } from '@wordpress/element';
import { Button, Spinner } from '@wordpress/components';
import { arrowRight } from '@wordpress/icons';
import apiFetch from '@wordpress/api-fetch';

/**
 * Internal dependencies
 */
import {
	UserMessage,
	AssistantMessage,
	PendingAssistantMessage,
} from './components/chat-message';
import {
	ConversationProvider,
	useConversation,
} from './context/conversation-provider';

interface Message {
	text: string;
	role: 'user' | 'assistant';
}

const ChatAppContent = () => {
	const { messages, addMessage } = useConversation();
	const [ inputValue, setInputValue ] = useState( '' );
	const [ isLoading, setIsLoading ] = useState( false );

	const handleSendMessage = async () => {
		if ( ! inputValue.trim() || isLoading ) {
			return;
		}

		const newMessage: Message = {
			text: inputValue,
			role: 'user',
		};

		addMessage( newMessage );
		setInputValue( '' );
		setIsLoading( true );

		try {
			const response = await apiFetch< { response: string } >( {
				path: '/wp/v2/demo-chat',
				method: 'POST',
				data: {
					message: inputValue,
				},
			} );

			addMessage( { text: response.response, role: 'assistant' } );
		} catch ( error ) {
			// Handle error appropriately
			console.error( 'Failed to get response:', error );
		} finally {
			setIsLoading( false );
		}
	};

	return (
		<div className="chat-container">
			<div className="chat-header">
				<h2>Demo AI Assistant</h2>
			</div>
			<div className="chat-body">
				<div className="chat-messages">
					{ messages.map( ( message, index ) =>
						message.role === 'user' ? (
							<UserMessage key={ index } text={ message.text } />
						) : (
							<AssistantMessage
								key={ index }
								text={ message.text }
							/>
						)
					) }
					{ isLoading && <PendingAssistantMessage /> }
				</div>
				<div className="chat-input">
					<textarea
						className="chat-input-textarea"
						value={ inputValue }
						onChange={ ( e ) => {
							setInputValue( e.target.value );
						} }
						placeholder="Type your message..."
						onKeyDown={ ( e ) => {
							if ( e.key === 'Enter' && ! e.shiftKey ) {
								e.preventDefault();
								handleSendMessage();
							}
						} }
					/>
					<Button
						className="chat-input-submit"
						onClick={ handleSendMessage }
						disabled={ ! inputValue.trim() || isLoading }
						icon={ isLoading ? null : arrowRight }
					>
						{ isLoading ? (
							<Spinner />
						) : (
							<span className="screen-reader-text">Send</span>
						) }
					</Button>
				</div>
			</div>
		</div>
	);
};

export const ChatApp = () => {
	return (
		<ConversationProvider>
			<ChatAppContent />
		</ConversationProvider>
	);
};
