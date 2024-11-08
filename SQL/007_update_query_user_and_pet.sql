UPDATE Users 
SET email = 'newemail@example.com', password = 'new_hashed_password'
WHERE id = 1;

UPDATE Pets 
SET status = 'Adopted', additional_details = 'Adopted by a loving family'
WHERE id = 1;
