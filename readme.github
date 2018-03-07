THIS PROJECT IS INTENDED TO ADAPT A MOODLE FEATURE.

Moodle has many question types available for teachers to create quizzes using them. One of them is called 'Drag and drop into text' (internally it's type is called *ddwtos*).

There are cases where this type of question doesn't suit simply because in it's vanilla version you HAVE TO take the order of the answers a student use into account. I modified it so the adminstrator/teacher can choose whether or not it's gonna be taken into account.

### Database modification

The only database modification needed for this feature to work is to add a column in the {question_ddwtos} table. Simply run the query below to apply the modification (PostgreSQL).

ALTER TABLE {question_ddwtos} ADD COLUMN ordered SMALLINT
