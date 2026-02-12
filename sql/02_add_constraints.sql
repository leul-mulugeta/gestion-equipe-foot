ALTER TABLE commentaire
    ADD CONSTRAINT FK_Commentaire_Joueur
        FOREIGN KEY (idJoueur) REFERENCES joueur(id)
        ON DELETE CASCADE;

ALTER TABLE participant
    ADD CONSTRAINT FK_Participant_Joueur
        FOREIGN KEY (idJoueur) REFERENCES joueur(id),
    ADD CONSTRAINT FK_Participant_Rencontre
        FOREIGN KEY (idRencontre) REFERENCES rencontre(id)
        ON DELETE CASCADE,
    ADD CONSTRAINT UC_Participation
        UNIQUE (idJoueur, idRencontre),
    ADD CONSTRAINT CHK_Evaluation
        CHECK (evaluation BETWEEN 1 AND 5);
