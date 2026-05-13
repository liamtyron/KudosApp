<?php

// =============================================================================
// NOTES: User Entity & UserController
// Section: User entity + Symfony Security Bundle + Registration/Login
// Requirement: Users must be logged in to view or post Kudos
// =============================================================================


// =============================================================================
// FILE: src/Entity/User.php
// =============================================================================

// WHY: These two interfaces plug this class into Symfony's security system.
//   - UserInterface         → gives Symfony the identity info it needs (username, roles, identifier)
//   - PasswordAuthenticatedUserInterface → tells Symfony this user logs in with a password
// Without these, Symfony won't know how to authenticate this class.
//
//   class User implements UserInterface, PasswordAuthenticatedUserInterface


// -----------------------------------------------------------------------------
// UNIQUENESS — two layers on `username`
// -----------------------------------------------------------------------------

// HOW: #[ORM\UniqueConstraint] enforces uniqueness at the DATABASE level.
//      #[UniqueEntity] enforces it at the FORM VALIDATION level with a
//      friendly error message — so the user sees feedback before hitting the DB.
//
//   #[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
//   #[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]


// -----------------------------------------------------------------------------
// ROLES
// -----------------------------------------------------------------------------

// WHY: Stores roles like ROLE_ADMIN in the DB as a JSON array.
//      getRoles() always adds ROLE_USER automatically — so no user is ever roleless.
//      Symfony uses roles to control access (e.g. only ROLE_ADMIN can manage users).
//
//   #[ORM\Column]
//   private array $roles = [];
//
//   public function getRoles(): array
//   {
//       $roles = $this->roles;
//       $roles[] = 'ROLE_USER'; // every user gets this minimum role
//       return array_unique($roles);
//   }


// -----------------------------------------------------------------------------
// getUserIdentifier()
// -----------------------------------------------------------------------------

// WHAT: Required by UserInterface.
//       Returns the unique string Symfony uses to identify this user in the session.
//       Here it returns the username.
//       Think of it as: "what label does Symfony put on this user's session?"
//
//   public function getUserIdentifier(): string
//   {
//       return (string) $this->username;
//   }


// -----------------------------------------------------------------------------
// __serialize()
// -----------------------------------------------------------------------------

// HOW: Controls what gets stored in the session.
//      Replaces the real password hash with a CRC32C checksum — so the full
//      bcrypt hash is NEVER stored in the session, but Symfony can still detect
//      if the password changed between requests (and force a re-login if so).
//
//   public function __serialize(): array
//   {
//       $data = (array) $this;
//       $data["\0".self::class."\0password"] = hash('crc32c', $this->password);
//       return $data;
//   }


// -----------------------------------------------------------------------------
// eraseCredentials() — @deprecated
// -----------------------------------------------------------------------------

// WHAT: Previously used to wipe plain-text passwords from memory after login.
//       Deprecated in Symfony 7.3+ because __serialize() handles session safety now.
//       Kept as an empty stub for backwards compatibility — safe to leave empty.
//       Remove when upgrading to Symfony 8.
//
//   #[\Deprecated]
//   public function eraseCredentials(): void {}


// -----------------------------------------------------------------------------
// $isVerified
// -----------------------------------------------------------------------------

// WHY: Tracks whether a user has verified their email address.
//      Defaults to false on registration.
//      You can gate features behind isVerified() later —
//      e.g. only let verified users view or post Kudos.
//
//   #[ORM\Column]
//   private bool $isVerified = false;


// -----------------------------------------------------------------------------
// EXTRA FIELDS: firstName, lastName, email, profilePic
// -----------------------------------------------------------------------------

// WHAT: Custom fields added on top of the security-required ones.
//       These are just regular Doctrine columns — nothing special security-wise.
//       profilePic is nullable (user doesn't have to upload one).
//
//   #[ORM\Column(length: 50)]   private ?string $firstName  = null;
//   #[ORM\Column(length: 255)]  private ?string $lastName   = null;
//   #[ORM\Column(length: 255)]  private ?string $email      = null;
//   #[ORM\Column(length: 255, nullable: true)] private ?string $profilePic = null;


// =============================================================================
// FILE: src/Controller/UserController.php
// =============================================================================

// WHAT: This is a standard Symfony CRUD controller, likely generated by make:crud.
//       It handles admin-level listing, viewing, creating, editing, and deleting users.
//       All routes are prefixed with /user via the class-level #[Route('/user')] attribute.
//
// IMPORTANT: This controller does NOT hash passwords — it's a raw admin CRUD tool.
//            For user registration (with password hashing), you need a separate
//            RegistrationController that uses UserPasswordHasherInterface.


// -----------------------------------------------------------------------------
// index() — list all users
// -----------------------------------------------------------------------------

// WHAT: Fetches all users from the DB via UserRepository::findAll().
//       Passes them to user/index.html.twig for display.
//       Route: GET /user
//
//   public function index(UserRepository $userRepository): Response
//   {
//       return $this->render('user/index.html.twig', [
//           'users' => $userRepository->findAll(),
//       ]);
//   }


// -----------------------------------------------------------------------------
// new() — create a user
// -----------------------------------------------------------------------------

// HOW: Creates a blank User object, binds it to the User1Type form.
//      If form is submitted AND valid:
//        - persist() → stages the new user in Doctrine (marks it for insert)
//        - flush()   → actually writes it to the database
//      Then redirects back to the index page.
//
// NOTE: No password hashing here — this is admin CRUD only.
//       In a real registration flow, hash the password before persist() using:
//       $user->setPassword($passwordHasher->hashPassword($user, $plainPassword));
//
//   Route: GET|POST /user/new


// -----------------------------------------------------------------------------
// show(User $user) — view one user
// -----------------------------------------------------------------------------

// HOW: Symfony auto-resolves the User from the {id} in the URL.
//      This is called "param conversion" (or EntityValueResolver).
//      No manual DB query needed — Symfony fetches it automatically.
//      Throws a 404 automatically if the ID doesn't exist.
//
//   Route: GET /user/{id}


// -----------------------------------------------------------------------------
// edit() — update a user
// -----------------------------------------------------------------------------

// WHY: No persist() here — the entity is already tracked by Doctrine
//      (it was fetched from the DB by param conversion).
//      Only flush() is needed to save the changes.
//
//   Route: GET|POST /user/{id}/edit


// -----------------------------------------------------------------------------
// delete() — remove a user
// -----------------------------------------------------------------------------

// WHY: isCsrfTokenValid() protects against Cross-Site Request Forgery (CSRF) —
//      someone tricking a logged-in user's browser into sending a delete request.
//      If the token in the form doesn't match the expected one, the delete is
//      skipped silently and the user is just redirected.
//
//   Route: POST /user/{id}
//
//   if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->getString('_token'))) {
//       $entityManager->remove($user);
//       $entityManager->flush();
//   }


// =============================================================================
// BIG PICTURE — how these two files connect to the Kudos requirement
// =============================================================================

// 1. User.php implements UserInterface + PasswordAuthenticatedUserInterface
//    → Symfony's security system can now authenticate users with a password.
//
// 2. UserController.php gives you admin CRUD for managing users.
//
// 3. For the Kudos requirement ("must be logged in to view or post"):
//    → In security.yaml, add access_control rules to protect Kudos routes:
//       - { path: ^/kudos, roles: ROLE_USER }
//    → OR use #[IsGranted('ROLE_USER')] on KudosController methods.
//
// 4. You still need:
//    → RegistrationController  — to let users sign up (with password hashing)
//    → LoginController         — to let users log in (Symfony's form_login handles most of this)
//    → security.yaml           — to wire it all together



// view user deleted rows:
//  //         <tr>
//   //              <th>Id</th>
//                 <td>{{ user.id }}</td>
//     //        </tr>
//     //        <tr>
//                 <th>Roles</th>
//                 <td>{{ user.roles ? user.roles|json_encode : '' }}</td>
//             </tr>
//             <tr>
//                 <th>Password</th>
//                 <td>{{ user.password }}</td>
//             </tr>
