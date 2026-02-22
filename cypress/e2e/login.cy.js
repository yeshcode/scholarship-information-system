describe('Login Test', () => {
  it('should login successfully', () => {
    cy.visit('http://127.0.0.1:8000/login');

    cy.get('input[name="email"]').type('admin@example.com');
    cy.get('input[name="password"]').type('adminpass');
    cy.get('button[type="submit"]').click();

    cy.url().should('include', '/dashboard');
  });
});