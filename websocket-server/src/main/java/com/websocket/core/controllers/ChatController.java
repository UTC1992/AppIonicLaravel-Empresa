package com.websocket.core.controllers;

import java.util.Date;
import java.util.Random;

import org.springframework.messaging.handler.annotation.MessageMapping;
import org.springframework.messaging.handler.annotation.SendTo;
import org.springframework.stereotype.Controller;

import com.websocket.core.models.entity.Mensaje;

@Controller
public class ChatController {

	private String[] colores= {"red","green","blue","magenta","purple","orange"};
	@MessageMapping("/mensaje")
	@SendTo("/chat/mensaje")
	public Mensaje  recibeMensaje(Mensaje mensaje) {
		mensaje.setFecha(new Date().getTime());
		if(mensaje.getTipo().equals("NUEVO_USUARIO")) {
			mensaje.setColor(colores[new Random().nextInt(colores.length)]);
			mensaje.setTexto("Nuevo Usuario Conectado: ");
		}
		return mensaje;
	}
	
	@MessageMapping("/escribiendo")
	@SendTo("/chat/escribiendo")
	public String estaEscribiendo(String username) {
		return username.concat(" está escribiendo ...");
	}
}
