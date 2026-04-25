ALTER TABLE commentaire
    ADD CONSTRAINT FK_Commentaire_Joueur
        FOREIGN KEY (joueur_id) REFERENCES joueur(joueur_id)
        ON DELETE CASCADE;

ALTER TABLE participant
    ADD CONSTRAINT FK_Participant_Joueur
        FOREIGN KEY (joueur_id) REFERENCES joueur(joueur_id),
    ADD CONSTRAINT FK_Participant_Rencontre
        FOREIGN KEY (rencontre_id) REFERENCES rencontre(rencontre_id)
        ON DELETE CASCADE,
    ADD CONSTRAINT UC_Participation
        UNIQUE (joueur_id, rencontre_id),
    ADD CONSTRAINT CHK_Evaluation
        CHECK (evaluation BETWEEN 1 AND 5);
